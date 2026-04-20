<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    /**
     * ================== INDEX ==================
     */
    public function index()
    {
        $karyawans = Karyawan::with('user')->get();

        $departemens = Karyawan::whereNotNull('departemen')
            ->distinct()
            ->pluck('departemen')
            ->values();

        return view('admin.karyawan.index', compact('karyawans','departemens'));
    }

    /**
     * ================== STORE ==================
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:karyawans,nip|unique:penggunas,username',
            'telp' => ['required','regex:/^08[0-9]{8,11}$/'],
            'nama' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],
            'email' => 'required|email|max:100|unique:karyawans,email|unique:penggunas,username',

            // 🔥 ROLE
            'role' => 'required|in:tenant_relation,departemen',

            // 🔥 DEPARTEMEN (tidak selalu wajib)
            'departemen' => 'nullable|in:Operational,Engineering,Finance,Legal,Developer',

            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ], [
            'nama.regex' => 'Nama hanya boleh huruf dan spasi.',
            'telp.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // ================= VALIDATION ERROR =================
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $validated = $validator->validated();

            // 🔥 VALIDASI TAMBAHAN (LOGIC BISNIS)
            if ($validated['role'] === 'departemen' && empty($validated['departemen'])) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'departemen' => ['Departemen wajib dipilih']
                    ]
                ], 422);
            }

            // 🔑 Generate username & password
            $username = $validated['nip'];
            $passwordPlain = 'Tmp-' . strtoupper(Str::random(5));

            // ================= CREATE USER =================
            $user = Pengguna::create([
                'username' => $username,
                'password' => Hash::make($passwordPlain),
                'role' => 'karyawan',
                'is_active' => true,
                'must_change_password' => true,
            ]);

            // ================= CREATE KARYAWAN =================
            $karyawan = Karyawan::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'telp' => $validated['telp'],
                'email' => $validated['email'],
                'role' => $validated['role'],

                // 🔥 LOGIC UTAMA
                'departemen' => $validated['role'] === 'departemen'
                    ? $validated['departemen']
                    : null,

                'jenis_kelamin' => $validated['jenis_kelamin'],
                'status' => 'Aktif'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil ditambahkan',
                'data' => $karyawan,
                'akun' => [
                    'username' => $username,
                    'password' => $passwordPlain
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ================== UPDATE ==================
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'telp' => 'required|string|max:20',
                'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
                'departemen' => 'required|string|max:50',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'status' => 'required|in:Aktif,Nonaktif'
            ]);

            $karyawan->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'karyawan' => $karyawan,
                'message' => 'Karyawan berhasil diperbarui'
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ================== DELETE ==================
     */
    public function destroy(Karyawan $karyawan)
    {
        DB::beginTransaction();

        try {
            // hapus akun login
            $karyawan->user()->delete();

            // hapus karyawan
            $karyawan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ================== RESET PASSWORD ==================
     */
    public function resetPassword(Karyawan $karyawan)
    {
        DB::beginTransaction();

        try {
            $newPassword = 'Tmp-' . strtoupper(Str::random(5));

            $karyawan->user->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'new_password' => $newPassword
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}