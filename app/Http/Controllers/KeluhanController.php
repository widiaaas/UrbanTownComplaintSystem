<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Keluhan;
use App\Models\Departemen;
use App\Models\WorkOrder;
use App\Models\RiwayatPenangananWO;
use App\Models\RiwayatPenangananKeluhan;


class KeluhanController extends Controller
{
    public function index()
    {
        return view('penghuni.ajukanKeluhan');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // ================= CEK UNIT =================
        $unit = $user->unit;

        if (!$unit) {
            return response()->json([
                'message' => 'Unit tidak ditemukan'
            ], 404);
        }

        // ================= CEK PENGHUNI =================
        $riwayatAktif = $unit->penghuniAktif;

            if (
                !$riwayatAktif ||
                !$riwayatAktif->penghuni
            ) {

                return response()->json([
                    'message' => 'Penghuni aktif tidak ditemukan'
                ], 404);
            }

            $penghuni = $riwayatAktif->penghuni;

        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'lampiran' => ['nullable', 'array'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
        ], [
            'judul.required' => 'Judul keluhan wajib diisi',
            'judul.max' => 'Judul maksimal 150 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'lampiran.mimes' => 'Lampiran hanya boleh JPG, PNG, atau PDF',
            'lampiran.max' => 'Ukuran file maksimal 1MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // ================= UPLOAD FILE =================
        $filesPath = [];

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('keluhan', $filename, 'public');

                $filesPath[] = $path;
            }
        }

        // ================= GENERATE TICKET =================
        $noUnit = $unit->nomor_unit;

        $lastKeluhan = Keluhan::where('unit_id', $unit->id)
            ->latest()
            ->first();

        if ($lastKeluhan && $lastKeluhan->nomor_tiket) {
            // ambil angka terakhir dari nomor_tiket (4 digit terakhir)
            $lastNumber = (int) substr($lastKeluhan->nomor_tiket, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // format jadi 0001
        $urutan = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // hasil nomor_tiket
        $nomor_tiket = 'KEL/' . $noUnit . '/' . $urutan;

        // ================= SIMPAN =================
        $keluhan = Keluhan::create([
            'nomor_tiket' => $nomor_tiket,
            'unit_id' => $unit->id,
            'penghuni_id' => $penghuni->id,
            'judul' => trim($request->judul),
            'deskripsi' => trim($request->deskripsi),
            'lampiran_pengajuan' => $filesPath,
            'tanggal_pengajuan' => now(),
            
        ]);

        return response()->json([
            'message' => 'Keluhan berhasil dikirim',
            'nomor_tiket' => $nomor_tiket,
            'data' => $keluhan
        ], 201);
    }

    public function riwayat()
    {
        $user = auth()->user();

        $unit = $user->unit;

        if (!$unit) {

            abort(404, 'Unit tidak ditemukan');
        }

        /**
         * ============================================
         * RIWAYAT HUNIAN AKTIF
         * ============================================
         */
        $riwayatAktif = $unit->penghuniAktif;

        if (
            !$riwayatAktif ||
            !$riwayatAktif->penghuni
        ) {

            $keluhan = collect();

            return view(
                'penghuni.riwayatKeluhan',
                compact('keluhan')
            );
        }

        /**
         * ============================================
         * PENGHUNI AKTIF
         * ============================================
         */
        $penghuni = $riwayatAktif->penghuni;

        /**
         * ============================================
         * KELUHAN MILIK PENGHUNI
         * PADA UNIT INI
         * ============================================
         */
        $keluhan = Keluhan::with([
                'riwayatPenanganan',
                'penghuni',
                'workOrders.departemen'
            ])

            ->where('unit_id', $unit->id)

            ->where(
                'penghuni_id',
                $penghuni->id
            )

            ->whereIn('status', [
                'unassigned',
                'open',
                'on_progress',
                'close'
            ])

            ->latest()

            ->get();

        return view(
            'penghuni.riwayatKeluhan',
            compact('keluhan')
        );
    }

    public function keluhanMasuk()
    {
        $keluhan = Keluhan::with([
                'unit',
                'penghuni',
                'penanggungJawab'
            ])

            ->whereNull('penanggung_jawab_id')
            ->latest()
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'nomor_tiket' => $k->nomor_tiket,
                    'judul' =>$k->judul,
                    'deskripsi' => $k->deskripsi,
                    'tanggal' =>
                        optional(
                            $k->tanggal_pengajuan
                        )->format('d-m-Y H:i'),
                    'status' =>$k->status,
                    'lampiran' => $k->lampiran_pengajuan ?? [],
                    'unit' => [
                        'id' =>$k->unit?->id,
                        'nomor_unit' =>$k->unit?->nomor_unit
                    ],
                    'penghuni' => [
                        'id' =>$k->penghuni?->id,
                        'nama' => $k->penghuni?->nama,
                        'telepon' =>$k->penghuni?->telepon,
                    ],
                    'penanggungJawab' =>$k->penanggungJawab
                ];
            });

        return view(
            'tenantrelation.keluhan.keluhanMasuk',
            compact('keluhan')
        );
    }

    public function ambilKeluhan($id)
    {
        $user = auth()->user();

        $keluhan = Keluhan::with('penanggungJawab')->findOrFail($id);

        if ($keluhan->penanggungJawab) {
            return response()->json([
                'message' => 'Keluhan sudah diambil'
            ], 400);
        }

        $keluhan->update([
            'penanggung_jawab_id' => $user->karyawan->id,
            'status' => 'open',
            'taken_at' => now(),
        ]);

        return response()->json([
            'message' => 'Keluhan berhasil diambil',
            'data' => $keluhan->fresh('penanggungJawab')
        ]);
    }

    public function daftarPenanganan(Request $request)
    {
        $user = auth()->user();

         // 🔥 pakai query builder
        $query = $user->karyawan->keluhans()
        ->with(['unit', 'penghuni','workOrders.departemen']);

         // 🔹 FILTER STATUS KELUHAN
        if ($request->filled('status')) {
            $statuses = explode(',', $request->status);

            $statuses = array_map(function ($s) {
                return strtolower(str_replace(' ', '_', $s));
            }, $statuses);

            $query->whereIn('status', $statuses);
        }

        // 🔥 🔥 INI TAMBAHAN UNTUK WO 🔥 🔥
        if ($request->filled('wo_status')) {

            $woStatuses = explode(',', $request->wo_status);

            $woStatuses = array_map(function ($s) {
                return strtolower(str_replace(' ', '_', $s));
            }, $woStatuses);

            $query->whereHas('workOrders', function ($q) use ($woStatuses) {
                $q->whereIn('status', $woStatuses);
            });
        }
        // dd($statuses);


        $keluhan = $query
            ->latest()
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'nomor_tiket' => $k->nomor_tiket,
                    'waktu' => optional($k->created_at)->format('d-m-Y H:i'),
                    'penghuni' => $k->penghuni->nama ?? '-',
                    'unit' => $k->unit->nomor_unit ?? '-',
                    'status' => $k->status_label,
                ];
            });

        return view('tenantrelation.keluhan.daftarPenanganan', compact('keluhan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $keluhan = Keluhan::findOrFail($id);

        // ❗ JANGAN BOLEH UPDATE KALAU SUDAH CLOSE
        if ($keluhan->status === 'close') {
            return response()->json([
                'message' => 'Keluhan sudah ditutup dan tidak bisa diubah'
            ], 403);
        }

        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:open,on_progress'],
            'catatan' => ['nullable', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // ================= NORMALISASI =================
        $status = str_replace(' ', '_', strtolower($request->status));

        // ================= UPDATE DB =================
        $keluhan->update([
            'status' => $status
        ]);

        // ================= SIMPAN RIWAYAT =================
        if ($request->catatan) {
            RiwayatPenangananKeluhan::create([
                'keluhan_id' => $keluhan->id,
                'status' => $status,
                'judul' => 'Update Status',
                'deskripsi' => $request->catatan,
                'waktu' => now()
            ]);
        }

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'status' => $status
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        $keluhan = Keluhan::with([
            'unit',
            'penghuni',
            'riwayatPenanganan', 
            'workOrders.penanggungJawab.karyawan',
            'workOrders.riwayat'
        ])->findOrFail($id);

        // 🔥 SECURITY (WAJIB)
        if (
            $keluhan->penanggung_jawab_id !==
            $user->id
        ) {
            abort(403, 'Tidak punya akses ke keluhan ini');
        }
        $departemen = Departemen::select(
            'id',
            'nama_departemen'
        )
        ->orderBy('nama_departemen')
        ->get();

        $data = [
            'id' => $keluhan->id,
            'nomor_tiket' => $keluhan->nomor_tiket,
            'unit' => $keluhan->unit->nomor_unit ?? '-',
            'penghuni' => $keluhan->penghuni->nama ?? '-',
            'telepon' => $keluhan->penghuni->telepon ?? '-',
            'status' => strtolower(str_replace('_',' ', $keluhan->status ?? 'unassigned')),
            'waktu' => optional($keluhan->created_at)->format('d-m-Y H:i'),
        
            // 🔥 PENGAJUAN
            'pengajuan' => [
                'judul' => $keluhan->judul,
                'deskripsi' => $keluhan->deskripsi,
                'waktu' => optional($keluhan->created_at)->format('d-m-Y H:i'),
                'lampiran' => $keluhan->lampiran_pengajuan ?? [],
            ],
            
            // 🔥 RIWAYAT
            'riwayat_penanganan' => $keluhan->riwayatPenanganan
                ->sortBy('waktu')
                ->map(function ($r) {
                    return [
                        'judul' => $r->judul,
                        'deskripsi' => $r->deskripsi,
                        'status' => $r->status,
                        'waktu' => optional($r->waktu)->format('d-m-Y H:i'),
                        'lampiran' => $r->lampiran ?? []
                    ];
                })->values(),
            
            // Keputusan akhir 
            'keputusan_akhir' => $keluhan->keputusan,

            'tanggal_keputusan_format' => optional($keluhan->tanggal_keputusan)
                                            ->format('d-m-Y H:i'),

            'lampiran_keputusan' => $keluhan->lampiran_keputusan ?? [],

            'work_orders' => $keluhan->workOrders->map(function ($wo) {
                $pj = $wo->penanggungJawab;
                return [
                    'id' => $wo->id,
                    'no' => $wo->nomor_wo,
                    'dept' =>
                            $wo->departemen?->nama_departemen ?? '-',
                            'status' => $wo->status ?? 'pending',
                    'tanggal' => optional($wo->created_at)->format('d M Y H:i'),
                    'lokasi' => $wo->lokasi,
                    'instruksi' => $wo->instruksi,
                    'petugas' => $pj?->karyawan?->nama ?? '-',
                    'laporan' => $wo->riwayat
                        ->sortBy('waktu')
                        ->map(function ($r) {
                            return [
                                'status' => strtolower(
                                    str_replace( '_', ' ',$r->status ?? 'pending')),
                                'judul' => $r->judul?? 'Update Penanganan',
                                'deskripsi' =>$r->deskripsi ?? '',
                                'waktu' => $r->waktu? \Carbon\Carbon::parse($r->waktu
                                    )->format('d M Y H:i')
                                    : (
                                        $r->created_at
                                            ? $r->created_at->format(
                                                'd M Y H:i'
                                            )
                                            : '-'
                                    ),
            
                                'lampiran' =>$r->lampiran ?? []
                            ];
                        })
                        ->values()
                ];
            })->values()
        ];


        return view('tenantrelation.keluhan.detailKeluhan', compact('data','departemen'));
    }

    public function keputusanAkhir(Request $request, $id)
    {
        $keluhan = Keluhan::findOrFail($id);
        
        if ($keluhan->status === 'close') {

            return response()->json([
        
                'message' =>
                    'Keluhan sudah ditutup'
        
            ], 403);
        }
        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'keputusan' => ['required', 'string', 'min:5'],
            'lampiran' => ['nullable', 'array'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
        ], [
            'keputusan.required' => 'keputusan wajib diisi',
            'keputusan.min' => 'Deskripsi keputusan minimal 5 karakter',

            'lampiran.*.mimes' =>
            'Lampiran hanya boleh JPG, PNG, atau PDF',

            'lampiran.*.max' =>
            'Ukuran file maksimal 1MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // ================= UPLOAD FILE =================
        $filesPath = [];

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('keputusan', $filename, 'public');

                $filesPath[] = $path;
            }
        }

        // ================= SIMPAN KE KELUHAN =================
        $keluhan->update([
            'keputusan' => trim($request->keputusan),
            'tanggal_keputusan' => now(),
            'lampiran_keputusan' => array_merge(
                $keluhan->lampiran_keputusan ?? [],
                $filesPath
            ),
            'status' => 'close'
        ]);
        $keluhan->refresh(); 

        return response()->json([
            'message' =>'Keputusan berhasil disimpan & keluhan ditutup',
            'data' => [
                'status' =>   $keluhan->status,
                'keputusan' => $keluhan->keputusan,
                'tanggal_keputusan' =>
                    optional(
                        $keluhan->tanggal_keputusan
                    )->format('d-m-Y H:i'),
                'lampiran_keputusan' =>$keluhan->lampiran_keputusan ?? []
            ]
        ]);
    }

}