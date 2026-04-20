<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Keluhan;
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
        $penghuni = $unit->penghuniAktif;

        if (!$penghuni) {
            return response()->json([
                'message' => 'Penghuni aktif tidak ditemukan'
            ], 404);
        }

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
        $noUnit = $unit->no_unit;

        $lastKeluhan = Keluhan::where('unit_id', $unit->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastKeluhan && $lastKeluhan->ticket) {
            // ambil angka terakhir dari ticket (4 digit terakhir)
            $lastNumber = (int) substr($lastKeluhan->ticket, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // format jadi 0001
        $urutan = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // hasil ticket
        $ticket = 'KEL/' . $noUnit . '/' . $urutan;

        // ================= SIMPAN =================
        $keluhan = Keluhan::create([
            'ticket' => $ticket,
            'unit_id' => $unit->id,
            'penghuni_id' => $penghuni->id,
            'judul' => trim($request->judul),
            'deskripsi' => trim($request->deskripsi),
            'lampiran' => $filesPath,
            
        ]);

        return response()->json([
            'message' => 'Keluhan berhasil dikirim',
            'ticket' => $ticket,
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

        $keluhan = Keluhan::with(['riwayat'])
            ->where('unit_id', $unit->id)
            ->whereIn('status', ['open', 'on progress', 'close'])
            ->latest()
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'ticket' => $k->ticket,
                    'title' => $k->judul,
                    'status' => $k->status,
                    'date' => optional($k->created_at)->format('d-m-Y H:i'),

                    // 🔥 PENGAJUAN (PENGHUNI)
                    'pengajuan' => [
                        'deskripsi' => $k->deskripsi,
                        'tanggal' => optional($k->created_at)->format('d-m-Y H:i'),
                        'lampiran' => $k->lampiran ?? [],
                    ],

                    // 🔥 KEPUTUSAN (TIM RESPON)
                    'keputusan' => $k->riwayat->map(function ($r) {
                        return [
                            'isi' => $r->keterangan, // ✅ FIX
                            'tanggal' => optional($r->waktu)->format('d-m-Y H:i'), // ✅ FIX
                            'lampiran' => $r->lampiran ?? [],
                        ];
                    })->values()
                ];
            });

        return view('penghuni.riwayatKeluhan', compact('keluhan'));
    }

    public function keluhanMasuk()
    {
        $keluhan = Keluhan::with(['unit', 'penghuni', 'penanggungJawab'])
            ->whereDoesntHave('penanggungJawab') // 🔥 RELASI
            ->latest()
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'ticket' => $k->ticket,
                    'unit' => $k->unit->no_unit ?? '-',
                    'tanggal' => optional($k->created_at)->format('d-m-Y H:i'),
                    'penghuni' => $k->penghuni->nama ?? '-',
                    'telepon' => $k->penghuni->telepon ?? '-',
                    'judul' => $k->judul,
                    'deskripsi' => $k->deskripsi,
                    'lampiran' => $k->lampiran ?? [],
                    'status' => 'Unassign',
                    'penanggungJawab' => null
                ];
            });

        return view('tenantrelation.keluhan.keluhanMasuk', compact('keluhan'));
    }

    public function ambilKeluhan($id)
    {
        $user = auth()->user();

        $keluhan = Keluhan::with('penanggungJawab')->findOrFail($id);

        // ❌ kalau sudah punya relasi
        if ($keluhan->penanggungJawab) {
            return response()->json([
                'message' => 'Keluhan sudah diambil'
            ], 400);
        }

        // ✅ assign
        $keluhan->penanggungJawab()->associate($user);
        $keluhan->status = 'open';
        $keluhan->taken_at = now();
        $keluhan->save();

        return response()->json([
            'message' => 'Keluhan berhasil diambil'
        ]);
    }

    public function daftarPenanganan()
    {
        $user = auth()->user();

        $keluhan = $user->keluhanDiambil()
            ->with(['unit', 'penghuni'])
            ->latest()
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'ticket' => $k->ticket,
                    'tanggal' => optional($k->created_at)->format('d-m-Y H:i'),
                    'penghuni' => $k->penghuni->nama ?? '-',
                    'unit' => $k->unit->no_unit ?? '-',
                    'status' => strtolower(str_replace('_', ' ', $k->status ?? 'open'))
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
                'keterangan' => $request->catatan,
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
            'riwayat',
            'workOrders.penanggungJawab.karyawan',
            'workOrders.riwayat'
        ])->findOrFail($id);

        // 🔥 SECURITY (WAJIB)
        if ($keluhan->penanggung_jawab_id !== $user->id) {
            abort(403, 'Tidak punya akses ke keluhan ini');
        }
        $departemen = [
            'Operational',
            'Engineering',
            'Finance',
            'Legal',
            'Developer'
        ];

        $data = [
            'id' => $keluhan->id,
            'ticket' => $keluhan->ticket,
            'unit' => $keluhan->unit->no_unit ?? '-',
            'penghuni' => $keluhan->penghuni->nama ?? '-',
            'telepon' => $keluhan->penghuni->telepon ?? '-',
            'status' => strtolower(str_replace('_',' ', $keluhan->status ?? 'open')),
            'tanggal' => optional($keluhan->created_at)->format('d-m-Y H:i'),
        
            // 🔥 PENGAJUAN
            'pengajuan' => [
                'judul' => $keluhan->judul,
                'deskripsi' => $keluhan->deskripsi,
                'tanggal' => optional($keluhan->created_at)->format('d-m-Y H:i'),
                'lampiran' => $keluhan->lampiran ?? [],
            ],
        
            // 🔥 RIWAYAT
            'keputusan' => $keluhan->riwayat
                ->sortBy('waktu')
                ->map(function ($r) {
                    return [
                        'isi' => $r->keterangan,
                        'tanggal' => optional($r->waktu)->format('d-m-Y H:i'),
                        'lampiran' => $r->lampiran ?? []
                    ];
                })->values(),
        
            // 🔥 INI YANG KURANG
            'work_orders' => $keluhan->workOrders->map(function ($wo) {
                $pj = $wo->penanggungJawab; 
                return [
                    'id' => $wo->id,
                    'no' => $wo->nomor_wo,
                    'dept' => $wo->departemen_tujuan,
                    'status' => $wo->status,
                    'tanggal' => optional($wo->created_at)->format('d M Y H:i'),
                    'lokasi' => $wo->lokasi,
                    'instruksi' => $wo->instruksi,
                    'petugas' => $pj 
                        ? ($pj->karyawan?->nama ?? $pj->username) 
                        : '-',
            
                    // 🔥 INI YANG KURANG
                    'laporan' => $wo->riwayat->map(function ($r) {

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
                            'ket' => $ket, // ✅ FIX
                            'waktu' => optional($r->waktu)->format('d M Y H:i'),
                            'lampiran' => $r->lampiran ?? []
                        ];
                    })
                ];
            })->values()
        ];


        return view('tenantrelation.keluhan.detailKeluhan', compact('data','departemen'));
    }

    public function keputusanAkhir(Request $request, $id)
    {
        $keluhan = Keluhan::findOrFail($id);

        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:150'],
            'solusi' => ['required', 'string', 'min:5'],
            'lampiran' => ['nullable', 'array'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'judul.required' => 'Judul wajib diisi',
            'solusi.required' => 'Solusi wajib diisi',
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
            'keputusan' => $request->judul . "\n\n" . $request->solusi,
            'tanggal_keputusan' => now(),
            'lampiran' => $filesPath, // 🔥 overwrite atau bisa merge kalau mau
            'status' => 'close'
        ]);

        return response()->json([
            'message' => 'Keputusan berhasil disimpan & keluhan ditutup'
        ]);
    }


    ////  BUAT WORK ORDER
    public function storeWO(Request $request, $id)
    {
        $keluhan = Keluhan::findOrFail($id);

        // VALIDASI
        $request->validate([
            'departemen' => 'required',
            'instruksi' => 'required|string',
            'lokasi' => 'required|string'
        ]);

        // GENERATE NOMOR WO
        $last = WorkOrder::latest()->first();
        $no = $last ? ((int) substr($last->nomor_wo, -3)) + 1 : 1;

        $nomorWO = 'WO-' . date('Y') . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);

        // SIMPAN
        $wo = WorkOrder::create([
            'nomor_wo' => $nomorWO,
            'keluhan_id' => $keluhan->id,
            'departemen_tujuan' => $request->departemen,
            'instruksi' => $request->instruksi,
            'status' => 'open'
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

        if ($keluhan->workOrders()->exists()) {
            return response()->json([
                'message' => 'Work Order sudah ada untuk keluhan ini'
            ], 400);
        }
    }
}