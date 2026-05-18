<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
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

            'nama' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s]+$/'],

            'telepon' => ['required', 'regex:/^08[0-9]{8,11}$/'],

            'email' => ['nullable', 'email', 'max:100', 'unique:penghunis,email'],

            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],

        ], [

            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh huruf dan spasi',
            'telepon.required' => 'Nomor telepon wajib diisi',
            'telepon.regex' => 'Nomor telepon harus diawali 08 dan 10-13 digit',
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
                'nama' => trim($request->nama),
                'email' => $request->email,
                'telepon' => $request->telepon,
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

            'nama' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s]+$/'],

            'telepon' => ['required', 'regex:/^08[0-9]{8,11}$/'],

            'email' => ['nullable', 'email', 'max:100', 'unique:penghunis,email,' . $penghuni->id],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],

        ], [

            'nama.regex' => 'Nama hanya boleh huruf dan spasi.',

            'telepon.regex' => 'Nomor telepon harus diawali 08 dan 10-13 digit.',

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

    // /**
    //  * ================= DELETE =================
    //  */
    // public function destroy(Request $request, Penghuni $penghuni)
    // {
    //     // CEK MASIH MENEMPATI UNIT
    //     if ($penghuni->unit_id !== null) {

    //         return response()->json([

    //             'success' => false,

    //             'message' => 'Penghuni masih menempati unit'

    //         ], 422);
    //     }

    //     $penghuni->delete();

    //     return response()->json([

    //         'success' => true,

    //         'message' => 'Penghuni berhasil dihapus'
    //     ]);
    // }
}