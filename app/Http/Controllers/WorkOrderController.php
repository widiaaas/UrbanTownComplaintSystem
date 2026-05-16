<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\WorkOrder;
use App\Models\Karyawan;
use App\Models\RiwayatPenangananWorkOrder;
use Illuminate\Support\Facades\Validator;

class WorkOrderController extends Controller
{
    public function store(Request $request, $keluhan_id)
    {
        $keluhan = Keluhan::findOrFail($keluhan_id);

        // VALIDASI: hanya 1 WO
        if ($keluhan->workOrders()->exists()) {

            return response()->json([
                'message' => 'Work Order sudah ada'
            ], 400);
        }

        // VALIDASI INPUT
        $validator = Validator::make($request->all(), [

            'departemen' => ['required','exists:departemens,id'],
            'instruksi' => ['required','string','min:5'],
            'lokasi' => ['required','string','max:255'
            ]

        ], [
            'departemen.required' =>'Departemen wajib dipilih',
            'departemen.exists' =>'Departemen tidak ditemukan',
            'instruksi.required' =>'Instruksi wajib diisi',
            'instruksi.min' =>'Instruksi minimal 5 karakter',
            'lokasi.required' =>'Lokasi wajib diisi',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // GENERATE NOMOR WO
        $lastWO = WorkOrder::latest()->first();

        if ($lastWO && $lastWO->nomor_tiket) {
            $lastNumber =
                (int) substr($lastWO->nomor_tiket, -4);
            $newNumber = $lastNumber + 1;

        } else {
            $newNumber = 1;
        }

        $urutan = str_pad(
            $newNumber,4,'0',STR_PAD_LEFT
        );

        $nomorWO ='WO/' .date('Y') .'/' .$urutan;

        // SIMPAN WO
        $wo = WorkOrder::create([
            'nomor_tiket' => $nomorWO,
            'keluhan_id' => $keluhan->id,
            'departemen_id' =>$request->departemen,
            'lokasi' =>trim($request->lokasi),
            'instruksi' => trim($request->instruksi),
        ]);

        $wo->load('departemen');

        // UPDATE STATUS KELUHAN
        $keluhan->update([
            'status' => 'on_progress'
        ]);

        return response()->json([

            'message' =>'Work Order berhasil dibuat',
            'data' => [
                'id' => $wo->id,
                'no' => $wo->nomor_tiket,
                'dept' =>
                    $wo->departemen
                        ?->nama_departemen ?? '-',
                'instruksi' =>
                    $wo->instruksi,
                'status' =>
                    $wo->status,
                'tanggal' =>
                    optional($wo->created_at)
                        ->format('d-m-Y H:i'),
                'lokasi' =>
                    $wo->lokasi,

                'laporan' => []
            ]
        ]);
    }

    public function woMasuk()
    {   
        $user = auth()->user();
        $karyawan = $user->karyawan;

        if (!$karyawan || !$karyawan->departemen) {
            abort(403, 'Departemen tidak ditemukan');
        }
        $wo = WorkOrder::with([
            'keluhan.unit',
            'keluhan.penghuni',
            'keluhan.penanggungJawab'
        ])
        ->whereNull('penanggung_jawab_id')
        ->where('departemen_id', $karyawan->departemen_id)
        ->latest()
        ->get()
        ->map(function ($item, $index) {

            $keluhan = $item->keluhan;
            $pj = $keluhan?->penanggungJawab;
            $karyawanPJ = $pj;
            $petugas = $item->penanggungJawab;

            return [
                'no' => $index + 1,
                'id' => $item->id,
                'no' => $item->nomor_tiket,
                'unit' => $keluhan?->unit?->nomor_unit ?? '-',
                'tanggal' => optional($item->created_at)->format('d-m-Y H:i'),

                'penghuni' => $keluhan?->penghuni?->nama ?? '-',
                'telepon' => $keluhan?->penghuni?->telepon ?? '-',

                'instruksi' => $item->instruksi,
                'lampiran' => $item->lampiran ?? [],

                // 🔥 TR (dari keluhan)
                'tr' => $pj?->nama ?? '-',

                // 🔥 Petugas WO
                'petugas' => $petugas?->nama ?? '-',
            ];
        })
        ->values(); // biar index rapi

        return view('departemen.workOrder.workOrderMasuk', compact('wo'));
    }

    public function ambilWO($id)
    {
        $user = auth()->user();

        $wo = WorkOrder::findOrFail($id);

        $wo->update([
            'penanggung_jawab_id' => $user->karyawan->id,
            'status' => 'open',
            'taken_at' => now()
        ]);

        return response()->json([
            'message' => 'WO berhasil diambil'
        ]);
    }

    public function daftarPenanganan()
    {
        $user = auth()->user();

        $wo = WorkOrder::with([
            'keluhan.unit',
            'keluhan.penghuni',
            'riwayat',
            'penanggungJawab' // 🔥 WAJIB
        ])
        ->where('penanggung_jawab_id',$user->karyawan->id)
        ->latest()
        ->get()
        ->values()
        ->map(function ($item) {
            
            $pj = $item->penanggungJawab;
            return [
                'id' => $item->id,
                'no' => $item->nomor_tiket,
                'unit' => $item->keluhan->unit->nomor_unit ?? '-',
                'tanggal' => optional($item->created_at)->format('d M Y H:i'),
                
                'status' => ucwords(str_replace('_', ' ', $item->status)),

                'deskripsi' => $item->keluhan->deskripsi ?? '-',
                'requestor' => $item->keluhan->penghuni->nama ?? '-',
                'telepon' => $item->keluhan->penghuni->telepon ?? '-',
                'instruksi' => $item->instruksi,
                'lokasi' => $item->lokasi,
                
                'petugas' => $pj 
                    ? ($pj->nama ?? $pj->username) 
                    : '-',
                // 🔥 INI YANG BIKIN LAPORAN MUNCUL
                'laporan' => $item->riwayat
                    ->sortBy('waktu') // 🔥 biar urut
                    ->values()
                    ->map(function ($r) {

                    $judul = 'Update Penanganan';
                    $deskripsi = $r->deskripsi;
                
                    if ($r->deskripsi && str_contains($r->deskripsi, ' - ')) {
                        $split = explode(' - ', $r->deskripsi);
                        $judul = $split[0];
                        $deskripsi = implode(' - ', array_slice($split, 1));
                    }
                
                    return [
                        'status' => $r->status,
                        'judul' => $judul,
                        'deskripsi' => $deskripsi,
                        'waktu' => optional($r->waktu)->format('d M Y H:i'),
                        'lampiran' => $r->lampiran ?? [] 
                    ];
                }),

                'lampiran' => $item->lampiran ?? []
            ];
        });

        return view('departemen.workOrder.daftarPenangananWO', [
            'wo' => $wo
        ]);
    }

    public function detail(Request $request)
    {
        $id = $request->id;

        $wo = WorkOrder::with([
            'keluhan.unit',
            'keluhan.penghuni',
            'penanggungJawab',
            'keluhan.penanggungJawab',
            'riwayat'
        ])->findOrFail($id);
        
        $pj = $wo->penanggungJawab;
        $tr = $wo->keluhan->penanggungJawab;
     
        $data = [
            'id' => $wo->id,
            'no' => $wo->nomor_tiket,
            'deskripsi' => $wo->keluhan->deskripsi ?? '-',
            'departemen' => $wo->departemen,
            'instruksi' => $wo->instruksi,
            'status' => ucwords(str_replace('_', ' ', $wo->status)),
            'petugas' => $pj 
                ? ($pj->nama ?? $pj->username) 
                : '-',
            'tr' => $tr?->nama ?? '-',
            'tanggal' => optional($wo->created_at)->format('d M Y H:i'),
            'lampiran' => $wo->lampiran ?? [],
            'lokasi' => $wo->lokasi,

            // 🔥 RIWAYAT
            'laporan' => $wo->riwayat()
                ->orderBy('waktu','asc')
                ->get()
                ->map(function ($r) {
                    return [
                        'status' => $r->status,
                        'judul' => $r->judul,
                        'deskripsi' => $r->deskripsi,
                        'waktu' => $r->waktu
                        ? \Carbon\Carbon::parse($r->waktu)->format('d M Y H:i')
                        : ($r->created_at 
                            ? $r->created_at->format('d M Y H:i') 
                            : '-'),
                        'lampiran' => $r->lampiran ?? []
                    ];
                })->values()
        ];

        return view('departemen.workOrder.detailWorkOrder', [
            'wo' => $data]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $user = auth()->user();

        $wo = WorkOrder::findOrFail($id);

        $status = strtolower(str_replace(' ', '_', $request->status));

        $wo->update([
            'status' => $status
        ]);

        RiwayatPenangananWorkOrder::create([
            'work_order_id' => $wo->id,
            'status' => $status,
            'judul' => 'Update Status', 
            'deskripsi' => 'Status diubah menjadi ' . $request->status,
            'waktu' => now()
        ]);

        return response()->json([
            'message' => 'Status berhasil diperbarui'
        ]);
    }

}