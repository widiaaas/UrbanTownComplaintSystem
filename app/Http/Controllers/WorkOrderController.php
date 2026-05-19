<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\WorkOrder;
use App\Models\Karyawan;
use App\Models\Departemen;
use App\Models\RiwayatPenangananWorkOrder;
use App\Models\RiwayatPenangananKeluhan;
use Illuminate\Support\Facades\Validator;

class WorkOrderController extends Controller
{
    public function store(Request $request, $keluhan_id)
    {
        $keluhan = Keluhan::findOrFail($keluhan_id);

        // hanya 1 WO
        if ($keluhan->workOrders()->exists()) {
            return response()->json([
                'message' => 'Work Order sudah ada'
            ], 400);
        }

        // validasi inputt
        $validator = Validator::make($request->all(), [

            'departemen' => ['required','exists:departemens,id'],
            'instruksi' => ['required','string','min:5'],
            'lokasi' => ['required','string','max:255'],
            'lampiran' => ['nullable','array'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf','max:1024']

        ], [
            'departemen.required' =>'Departemen wajib dipilih',
            'departemen.exists' =>'Departemen tidak ditemukan',
            'instruksi.required' =>'Instruksi wajib diisi',
            'instruksi.min' =>'Instruksi minimal 5 karakter',
            'lokasi.required' =>'Lokasi wajib diisi',
            'lampiran.*.mimes' =>'Lampiran hanya boleh JPG, PNG, atau PDF',
            'lampiran.*.max' =>'Ukuran file maksimal 1MB',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // upload file 
        $lampiran = [];
            if ($request->hasFile('lampiran')) {
                foreach (
                    $request->file('lampiran')
                    as $file
                ) {
                    if ($file->isValid()) {
                        $lampiran[] = $file->store('work_order','public');
                    }
                }
        }

        // generate nomor wo
        $tahun = date('Y');
        $departemen = Departemen::findOrFail($request->departemen);
        $words = explode(' ', strtoupper($departemen->nama_departemen));
        $kodeDept = '';

        foreach ($words as $word) {
            $kodeDept .= substr($word, 0, 1);
        }

        if (strlen($kodeDept) < 2) {
            $kodeDept = strtoupper(
                substr(
                    preg_replace(
                        '/\s+/','',$departemen->nama_departemen),0,3)
            );
        }

        $lastWO = WorkOrder::whereYear('created_at',$tahun)
            ->latest()
            ->first();
        if ($lastWO &&preg_match('/(\d+)$/',$lastWO->nomor_tiket,$matches)) {
            $newNumber =(int) $matches[1] + 1;
        } else {
            $newNumber = 1;
        }

        $urutan = str_pad($newNumber, 4,'0', STR_PAD_LEFT);
        $nomor_tiket ='WO-' .$tahun . '-' . $kodeDept .'-' .$urutan;

        // SIMPAN WO
        $wo = WorkOrder::create([
            'nomor_tiket' => $nomor_tiket,
            'keluhan_id' => $keluhan->id,
            'departemen_id' =>$request->departemen,
            'lokasi' =>trim($request->lokasi),
            'instruksi' => trim($request->instruksi),
            'lampiran' => $lampiran,
        ]);

        $wo->load('departemen');

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => $keluhan->id,
            'judul' => 'Work Order telah dibuat',
            'deskripsi' =>'Work Order telah dibuat dan diteruskan ke departemen terkait untuk proses penanganan',
            'status' => 'on_progress',
            'waktu' => now(),
            'lampiran' => []
        ]);

        return response()->json([

            'message' =>'Work Order berhasil dibuat',
            'data' => [
                'id' => $wo->id,
                'nomor_tiket' => $wo->nomor_tiket,
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

                'lampiran' => $wo->lampiran ?? [],
                'laporan' => [],
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
        ->where('status', 'unassigned')
        ->where('departemen_id', $karyawan->departemen_id)
        ->latest()
        ->get()
        ->map(function ($item, $index) {

            $keluhan = $item->keluhan;
            $pj = $keluhan?->penanggungJawab;
            $karyawanPJ = $pj;
            $petugas = $item->penanggungJawab;

            return [
                
                'id' => $item->id,
                'nomor_tiket' => $item->nomor_tiket,
                'unit' => $keluhan?->unit?->nomor_unit ?? '-',
                'tanggal' => optional($item->created_at)->format('d-m-Y H:i'),
                'penghuni' => $keluhan?->penghuni?->nama ?? '-',
                'no_telepon' => $keluhan?->penghuni?->no_telepon ?? '-',
                'instruksi' => $item->instruksi,
                'lampiran' => $item->lampiran ?? [],

                // Pengajuan penghuuni 
                'judul_keluhan' =>$keluhan?->judul ?? '-',
                'deskripsi_keluhan' =>$keluhan?->deskripsi ?? '-',
                'lampiran_pengajuan' =>$keluhan?->lampiran_pengajuan ?? [],

                //  TR (dari keluhan)
                'tr' => $pj?->nama ?? '-',
                // Petugas WO
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

        RiwayatPenangananWorkOrder::create([
            'work_order_id' => $wo->id,
            'judul' => 'Work Order Diambil Alih',
            'deskripsi' =>'Work Order telah diambil dan mulai diproses oleh departemen',
            'status' => 'open',
            'waktu' => now(),
            'lampiran' => []
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
                'nomor_tiket' => $item->nomor_tiket,
                'unit' => $item->keluhan->unit->nomor_unit ?? '-',
                'tanggal' => optional($item->created_at)->format('d M Y H:i'),
                
                'status' => ucwords(str_replace('_', ' ', $item->status)),

                'deskripsi' => $item->keluhan->deskripsi ?? '-',
                'requestor' => $item->keluhan->penghuni->nama ?? '-',
                'no_telepon' => $item->keluhan->penghuni->no_telepon ?? '-',
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

        $user = auth()->user();

        $karyawan = $user->karyawan;

        $wo = WorkOrder::with([

            'keluhan.unit',

            'keluhan.penghuni',

            'departemen',

            'penanggungJawab',

            'keluhan.penanggungJawab',

            'riwayat'

        ])->findOrFail($id);

        // ================= READONLY =================

        $readonly = false;

        // BUKAN PETUGAS WO
        if (
            $wo->penanggung_jawab_id !==
            $karyawan->id
        ) {

            $readonly = true;
        }

        $pj = $wo->penanggungJawab;

        $tr = $wo->keluhan->penanggungJawab;

        $data = [

            'id' => $wo->id,

            'nomor_tiket' =>
                $wo->nomor_tiket,

            'deskripsi' =>
                $wo->keluhan->deskripsi ?? '-',

            'departemen' =>
                $wo->departemen,

            'instruksi' =>
                $wo->instruksi,

            'status' =>
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $wo->status
                    )
                ),

            'petugas' => $pj
                ? ($pj->nama ?? '-')
                : 'Belum ada petugas',

            'tr' =>
                $tr?->nama
                ?? 'Belum ada penanggung jawab',

            'tanggal' =>
                optional($wo->created_at)
                    ->format('d M Y H:i'),

            'lampiran' =>
                $wo->lampiran ?? [],

            'lokasi' =>
                $wo->lokasi,

            // ================= PENGAJUAN =================

            'judul_keluhan' =>
                $wo->keluhan->judul ?? '-',

            'penghuni' =>
                $wo->keluhan
                    ->penghuni
                    ->nama ?? '-',

            'no_telepon' =>
                $wo->keluhan
                    ->penghuni
                    ->no_telepon ?? '-',

            'unit' =>
                $wo->keluhan
                    ->unit
                    ->nomor_unit ?? '-',

            'lampiran_pengajuan' =>
                $wo->keluhan
                    ->lampiran_pengajuan ?? [],

            // ================= RIWAYAT =================

            'laporan' => $wo->riwayat()
                ->orderBy('waktu', 'asc')
                ->get()
                ->map(function ($r) {

                    return [

                        'status' =>
                            $r->status,

                        'judul' =>
                            $r->judul,

                        'deskripsi' =>
                            $r->deskripsi,

                        'waktu' => $r->waktu

                            ? \Carbon\Carbon::parse(
                                $r->waktu
                            )->format('d M Y H:i')

                            : (

                                $r->created_at

                                ? $r->created_at
                                    ->format('d M Y H:i')

                                : '-'
                            ),

                        'lampiran' =>
                            $r->lampiran ?? []
                    ];
                })->values()
        ];

        return view(
            'departemen.workOrder.detailWorkOrder',
            [

                'wo' => $data,

                'readonly' => $readonly
            ]
        );
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

    public function riwayatWO(Request $request)
    {
        $user = auth()->user();

        $karyawan = $user->karyawan;

        if (!$karyawan) {

            abort(403, 'Karyawan tidak ditemukan');
        }

        $query = WorkOrder::with([

                'keluhan.unit',

                'keluhan.penghuni',

                'departemen',

                'penanggungJawab',

                'riwayat'

            ])

            // 🔥 HANYA DEPARTEMENNYA
            ->where(
                'departemen_id',
                $karyawan->departemen_id
            )

            ->latest();

        // ================= FILTER STATUS =================

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $wo = $query
            ->get()
            ->map(function ($item) {

                return [

                    'id' =>
                        $item->id,

                    'nomor_tiket' =>
                        $item->nomor_tiket,

                    'unit' =>
                        $item->keluhan
                            ?->unit
                            ?->nomor_unit ?? '-',

                    'penghuni' =>
                        $item->keluhan
                            ?->penghuni
                            ?->nama ?? '-',

                    'instruksi' =>
                        $item->instruksi,

                    'status' =>
                        $item->status,

                    'tanggal' =>
                        optional(
                            $item->created_at
                        )->format('d-m-Y H:i'),

                    'petugas' =>
                        $item->penanggungJawab
                            ?->nama
                            ?? 'Belum ada petugas',
                ];
            });

        return view(
            'departemen.workOrder.riwayatWO',
            compact('wo')
        );
    }

}