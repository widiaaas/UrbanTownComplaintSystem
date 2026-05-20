<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\RiwayatHunian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenghuniController extends Controller
{
    /**
     * ================= INDEX =================
     */
    public function index(Request $request)
    {
        $query = Penghuni::with('riwayatHunian.unit');


        // DATA
        $penghunis = $query
            ->latest()
            ->get();

        $jenisKelamin = [
            'Laki-laki',
            'Perempuan'
        ];

        return view(
            'admin.penghuni.index',
            compact(
                'penghunis',
                'jenisKelamin'
            )
        );
    }

    /**
     * ================= STORE =================
     */
    public function store(Request $request)
    {
        // VALIDASI AJAX
        if (!$request->expectsJson()) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nik' => ['required','string','digits:16','unique:penghunis,nik'],
            'nama' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s]+$/'],
            'no_telepon' => ['required', 'regex:/^08[0-9]{8,11}$/'],
            'email' => ['nullable', 'email', 'max:100', 'unique:penghunis,email'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],

        ], [
            'nik.digits' => 'NIK harus 16 digit angka',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh huruf dan spasi',
            'no_telepon.required' => 'Nomor no_telepon wajib diisi',
            'no_telepon.regex' => 'Nomor no_telepon harus diawali 08 dan 10-13 digit',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        ]);

        // VALIDATION ERROR
        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $penghuni = Penghuni::create([
                'nik' => $request->nik? trim($request->nik): null,
                'nama' => trim($request->nama),
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Penghuni berhasil ditambahkan',

                'data' => $penghuni
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'errors' => [
                    'system' => [$e->getMessage()]
                ]

            ], 500);
        }
    }

    /**
     * ================= UPDATE =================
     */
    public function update(Request $request, Penghuni $penghuni)
    {
        $validator = Validator::make($request->all(), [
            'nik' => ['required','string','digits:16','unique:penghunis,nik,' . $penghuni->id],
            'nama' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s]+$/'],
            'no_telepon' => ['required', 'regex:/^08[0-9]{8,11}$/'],
            'email' => ['nullable', 'email', 'max:100', 'unique:penghunis,email,' . $penghuni->id],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],

        ], [

            'nik.digits' => 'NIK harus 16 digit angka',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama.regex' => 'Nama hanya boleh huruf dan spasi.',
            'no_telepon.regex' => 'Nomor no_telepon harus diawali 08 dan 10-13 digit.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // VALIDATION ERROR
        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $data = $validator->validated();

            // // JIKA NONAKTIF → LEPAS UNIT
            // if ($data['status'] === 'Nonaktif') {

            //     $data['unit_id'] = null;
            // }

            $penghuni->update($data);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Penghuni berhasil diperbarui',

                'data' => $penghuni
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function keluarUnit(Penghuni $penghuni)
    {
        $riwayat = $penghuni->riwayatHunian()
            ->where('status', 'Aktif')
            ->latest()
            ->first();

        if (!$riwayat) {

            return response()->json([
                'success' => false,
                'message' => 'Penghuni tidak sedang menempati unit'

            ], 422);
        }

        $riwayat->update([
            'status' => 'Nonaktif',
            'tanggal_keluar' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil keluar dari unit'
        ]);
    }

    public function riwayatHunian(Request $request)
    {
        $riwayat = RiwayatHunian::with([
    
                'penghuni',
    
                'unit'
    
            ])
    
            ->latest()
    
            ->get()
    
            ->map(function ($r) {
    
                $penghuni = $r->penghuni;
    
                $unit = $r->unit;
    
                return [
    
                    'id' => $r->id,
    
                    // ================= PENGHUNI =================
    
                    'penghuni' =>
                        $penghuni?->nama ?? '-',
    
                    'nik' =>
                        $penghuni?->nik ?? '-',
    
                    'email' =>
                        $penghuni?->email ?? '-',
    
                    'no_telepon' =>
                        $penghuni?->no_telepon ?? '-',
    
                    'jenis_kelamin' =>
                        $penghuni?->jenis_kelamin ?? '-',
    
                    'alamat_asal' =>
                        $penghuni?->alamat_asal ?? '-',
    
                    // ================= UNIT =================
    
                    'unit' =>
                        $unit?->nomor_unit ?? '-',
    
                    'gedung' =>
                        $unit?->gedung ?? '-',
    
                    'lantai' =>
                        $unit?->lantai ?? '-',
    
                    // ================= RIWAYAT =================
    
                    'tanggal_masuk' =>
    
                        $r->tanggal_masuk
    
                            ? \Carbon\Carbon::parse(
                                $r->tanggal_masuk
                            )->format('d M Y')
    
                            : '-',
    
                    'tanggal_keluar' =>
    
                        $r->tanggal_keluar
    
                            ? \Carbon\Carbon::parse(
                                $r->tanggal_keluar
                            )->format('d M Y')
    
                            : '-',
    
                    'status' =>
                        $r->status,
    
                    'created_at' =>
    
                        optional($r->created_at)
                            ->format('d M Y H:i'),
                ];
            })
    
            ->values();
    
        return view(
    
            'admin.penghuni.riwayatHunian',
    
            compact('riwayat')
        );
    }
}