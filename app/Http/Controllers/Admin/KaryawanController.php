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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    private array $departemenList = [
        'Operational',
        'Engineering',
        'Finance',
        'Legal',
        'Developer'
    ];
    /**
     * ================== INDEX ==================
     */
    public function index(Request $request)
    {
        $query = Karyawan::with('user');

        // ================= FILTER NAMA =================
        if ($request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . trim($request->nama) . '%');
        }

        // ================= FILTER KATEGORI =================
        if ($request->filled('kategori')) {
            $kategori = $request->kategori;

            if ($kategori === 'tenant_relation') {
                $query->where('role', 'tenant_relation');
            } elseif (str_starts_with($kategori, 'dept:')) {
                $dept = str_replace('dept:', '', $kategori);

                $query->where('role', 'departemen')
                    ->where('departemen', $dept);
            }
        }

        // ================= DATA =================
        $karyawans = $query->latest()->get();

        // ================= LIST DEPARTEMEN =================
        $departemens = $this->departemenList;

        return view('admin.karyawan.index', compact('karyawans', 'departemens'));
    }

    /**
     * ================== STORE ==================
     */
    public function store(Request $request)
    {   
        $departemenList = $this->departemenList;

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:karyawans,nip|unique:penggunas,username',
            'telp' => ['required','regex:/^08[0-9]{8,11}$/'],
            'nama' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],
            'email' => 'required|email|max:100|unique:karyawans,email|unique:penggunas,username',

            // 🔥 ROLE
            'role' => 'required|in:tenant_relation,departemen',

            // 🔥 DEPARTEMEN (tidak selalu wajib)
            'departemen' => [
                'nullable',
                Rule::in($departemenList)
            ],

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
            $password = Str::random(8);
           

            // ================= CREATE USER =================
            $user = Pengguna::create([
                'username' => $username,
                'password' => Hash::make($password),
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
                    'password' => $password
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

                'nip' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('karyawans', 'nip')->ignore($karyawan->id),
                    Rule::unique('penggunas', 'username')->ignore($karyawan->user_id),
                ],
            
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[A-Za-z\s]+$/'
                ],
            
                'telp' => [
                    'required',
                    'regex:/^08[0-9]{8,11}$/'
                ],
            
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('karyawans', 'email')->ignore($karyawan->id),
                ],
            
                'role' => 'required|in:tenant_relation,departemen',
            
                'departemen' => [
                    'nullable',
                    Rule::in($this->departemenList)
                ],
            
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            
                'status' => 'required|in:Aktif,Nonaktif'
            
            ], [
                'nama.regex' => 'Nama hanya boleh huruf dan spasi.',
                'telp.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit.',
            ]);

            $karyawan->user->update([
                'username' => $validated['nip']
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