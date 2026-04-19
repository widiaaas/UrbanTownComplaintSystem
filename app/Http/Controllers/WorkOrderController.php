<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\WorkOrder;
use App\Models\RiwayatPenangananWorkOrder;
use Illuminate\Support\Facades\Validator;

class WorkOrderController extends Controller
{
    public function store(Request $request, $keluhan_id)
    {
        $keluhan = Keluhan::findOrFail($keluhan_id);

        // 🔒 VALIDASI: hanya 1 WO
        if ($keluhan->workOrders()->exists()) {
            return response()->json([
                'message' => 'Work Order sudah ada'
            ], 400);
        }

        $request->validate([
            'departemen' => 'required',
            'instruksi' => 'required|string',
            'lokasi' => 'required|string'
        ]);

        // GENERATE NO WO
        $last = WorkOrder::latest()->first();
        $no = $last ? ((int) substr($last->nomor_wo, -3)) + 1 : 1;

        $nomorWO = 'WO-' . date('Y') . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);

        $wo = WorkOrder::create([
            'nomor_wo' => $nomorWO,
            'keluhan_id' => $keluhan->id,
            'departemen_tujuan' => $request->departemen,
            'lokasi' => $request->lokasi,
            'instruksi' => $request->instruksi,
        ]);

        // 🔥 BONUS: otomatis ubah status keluhan
        $keluhan->update([
            'status' => 'on_progress'
        ]);

        return response()->json([
            'message' => 'Work Order berhasil dibuat',
            'data' => [
                'id' => $wo->id,
                'no' => $wo->nomor_wo,
                'dept' => $wo->departemen_tujuan,
                'instruksi' => $wo->instruksi,
                'status' => $wo->status,
                'tanggal' => $wo->created_at->format('d-m-Y H:i'),
                'lokasi' => $request->lokasi
            ]
        ]);
    }

    public function woMasuk()
    {   
        $wo = WorkOrder::with(['keluhan.unit', 'keluhan.penghuni','keluhan.penanggungJawab'])
            ->whereNull('penanggung_jawab_id')
            ->latest()
            ->get()
            ->values()
            ->map(function ($item, $index) {

                // dd([
                //     'pj_id' => $item->keluhan->penanggung_jawab_id,
                //     'relasi' => $item->keluhan->penanggungJawab
                // ]);
            
                return [
                    'no' => $index + 1,
                    'id' => $item->nomor_wo,
                    'unit' => $item->keluhan->unit->no_unit ?? '-',
                    'tanggal' => optional($item->created_at)->format('d-m-Y H:i'),
                    'penghuni' => $item->keluhan->penghuni->nama ?? '-',
                    'telepon' => $item->keluhan->penghuni->telepon ?? '-',
                    'instruksi' => $item->instruksi,
                    'lampiran' => $item->lampiran ?? [],
                    'tr' => $item->keluhan->penanggungJawab->name ?? '-',
                    'petugas' => optional($item->penanggungJawab->karyawan)->nama ?? '-',
                ];
            });

        return view('departemen.workOrder.workOrderMasuk', compact('wo')); // 🔥 INI WAJIB
    }

    public function ambilWO($id)
    {
        $user = auth()->user();

        $wo = WorkOrder::where('nomor_wo', $id)->firstOrFail();

        $wo->update([
            'penanggung_jawab_id' => $user->id,
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
            'penanggungJawab.karyawan' // 🔥 WAJIB
        ])
        ->where('penanggung_jawab_id', $user->id)
        ->latest()
        ->get()
        ->values()
        ->map(function ($item) {

            return [
                'id' => $item->id,
                'no' => $item->nomor_wo,
                'unit' => $item->keluhan->unit->no_unit ?? '-',
                'tanggal' => optional($item->created_at)->format('d M Y H:i'),

                'status' => ucfirst(str_replace('_', ' ', $item->status)),

                'tiket' => $item->keluhan->ticket ?? '-',
                'requestor' => $item->keluhan->penghuni->nama ?? '-',
                'telepon' => $item->keluhan->penghuni->telepon ?? '-',
                'instruksi' => $item->instruksi,
                'lokasi' => $item->lokasi,
                'petugas' => $item->penanggungJawab->karyawan->nama ?? '-',

                // 🔥 INI YANG BIKIN LAPORAN MUNCUL
                'laporan' => $item->riwayat
                    ->sortBy('waktu') // 🔥 biar urut
                    ->values()
                    ->map(function ($r) {

                    $judul = 'Update Penanganan';
                    $ket = $r->keterangan;
                
                    if ($r->keterangan && str_contains($r->keterangan, ' - ')) {
                        $split = explode(' - ', $r->keterangan);
                        $judul = $split[0];
                        $ket = implode(' - ', array_slice($split, 1));
                    }
                
                    return [
                        'status' => $r->status,
                        'judul' => $judul,
                        'ket' => $ket,
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
            'penanggungJawab.karyawan',
            'riwayat'
        ])->findOrFail($id);

        $data = [
            'id' => $wo->id,
            'no' => $wo->nomor_wo,
            'tiket' => $wo->keluhan->ticket ?? '-',
            'dept' => $wo->departemen_tujuan,
            'instruksi' => $wo->instruksi,
            'status' => ucfirst(str_replace('_', ' ', $wo->status)),
            'petugas' => $wo->penanggungJawab->karyawan->nama ?? '-',
            'tanggal' => optional($wo->created_at)->format('d M Y H:i'),
            'lampiran' => $wo->lampiran ?? [],
            'lokasi' => $wo->lokasi,

            // 🔥 RIWAYAT
            'laporan' => $wo->riwayat
                ->sortBy('waktu')
                ->values()
                ->map(function ($r) {

                    $judul = 'Update Penanganan';
                    $ket = $r->keterangan;

                    if ($r->keterangan && str_contains($r->keterangan, ' - ')) {
                        $split = explode(' - ', $r->keterangan);
                        $judul = $split[0];
                        $ket = implode(' - ', array_slice($split, 1));
                    }

                    return [
                        'status' => $r->status,
                        'judul' => $judul,
                        'ket' => $ket,
                        'waktu' => optional($r->waktu)->format('d M Y H:i'),
                        'lampiran' => $r->lampiran ?? [] // 🔥 INI YANG HILANG
                    ];
                })
        ];

        return view('departemen.workOrder.detailWorkOrder', [
            'wo' => $data
        ]);
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
            'keterangan' => 'Status diubah menjadi ' . $request->status,
            'penanggung_jawab_id' => $user->id,
            'waktu' => now()
        ]);

        return response()->json([
            'message' => 'Status berhasil diperbarui'
        ]);
    }
}