<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Pengguna;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{   

    // GENERATE PASSWORD
    private function generatePassword()
    {
        $chars =
            'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $random = '';

        for ($i = 0; $i < 6; $i++) {
            $random .=$chars[rand(0, strlen($chars) - 1)];
        }
        return 'APT-' . $random;
    }
    
    /**
     * ================== INDEX ==================
     */
    public function index(Request $request)
    {
        $query = Karyawan::with([
            'pengguna',
            'departemen'
        ])
        ->whereHas('pengguna', function ($q) {
    
            $q->whereIn('role', [
                'tenant_relation',
                'departemen'
            ]);
        });

        // ================= FILTER NAMA =================
        if ($request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . trim($request->nama) . '%');
        }

        // ================= FILTER KATEGORI =================
        if ($request->filled('kategori')) {

            $kategori = $request->kategori;
        
            // TR
            if ($kategori === 'tenant_relation') {
        
                $query->whereHas(
                    'pengguna',
                    function ($q) {
        
                        $q->where(
                            'role',
                            'tenant_relation'
                        );
                    }
                );
            }
        
            // Departemen
            elseif (str_starts_with($kategori, 'dept_')) {
        
                $departemenId = str_replace(
                    'dept_',
                    '',
                    $kategori
                );
        
                $query->where(
                    'departemen_id',
                    $departemenId
                );
            }
        }
        // ================= DATA =================
        $karyawans = $query->latest()->get();

        // ================= LIST DEPARTEMEN =================
        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('admin.karyawan.index', compact('karyawans', 'departemens'));
    }

    /**
     * ================== STORE ==================
     */
    public function store(Request $request)
    {   
        

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:karyawans,nip|unique:penggunas,username',
            'no_telepon' => ['required','regex:/^08[0-9]{8,11}$/'],
            'nama' => ['required','string','max:100','regex:/^[A-Za-z\s\.\']+$/'],
            'email' => 'required|email|max:100|unique:karyawans,email|unique:penggunas,username',

            // 🔥 ROLE
            'role' => 'required|in:tenant_relation,departemen',

            // 🔥 DEPARTEMEN (tidak selalu wajib)
            'departemen_id' => [
                'nullable',
                'exists:departemens,id'
            ],

            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ], [
            'nama.regex' => 'Nama hanya boleh huruf dan spasi.',
            'no_telepon.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit.',
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
            if ($validated['role'] === 'departemen' && empty($validated['departemen_id'] ?? null)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'departemen_id' => ['Departemen wajib dipilih']
                    ]
                ], 422);
            }

            // 🔑 Generate username & password
            $username = $validated['nip'];
            $newPassword = $this->generatePassword();
           

            // ================= CREATE USER =================
            $pengguna = Pengguna::create([
                'username' => $username,
                'password' => Hash::make($newPassword),
                'role' => $validated['role'],
                'is_active' => true,
                'must_change_password' => true,
            ]);

            // ================= CREATE KARYAWAN =================
            $karyawan = Karyawan::create([
                'pengguna_id' => $pengguna->id,
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'],
                

                // 🔥 LOGIC UTAMA
                'departemen_id' => $validated['role'] === 'departemen'
                    ? $validated['departemen_id']
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
                    'password' => $newPassword
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

        if (
            $karyawan->pengguna->role === 'admin'
        ) {
        
            abort(403);
        }

        DB::beginTransaction();

        try {
            $validated = $request->validate([

                'nip' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('karyawans', 'nip')->ignore($karyawan->id),
                    Rule::unique('penggunas', 'username')->ignore($karyawan->pengguna_id),
                ],
            
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[A-Za-z\s\.\']+$/'
                ],
            
                'no_telepon' => [
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
            
                'departemen_id' => [
                    'nullable', 'exists:departemens,id'
                ],
            
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            
                'status' => 'required|in:Aktif,Nonaktif'
            
            ], [
                'nama.regex' => 'Nama hanya boleh huruf dan spasi.',
                'no_telepon.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit.',
            ]);

            if (
                $validated['role'] !== 'departemen'
            ) {
            
                $validated['departemen_id'] = null;
            }

            $karyawan->pengguna->update([
                'username' => $validated['nip'],
                'role' => $validated['role'],
                'is_active' => $validated['status'] === 'Aktif'
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
     * ================== RESET PASSWORD ==================
     */
    public function resetPassword(Karyawan $karyawan)
    {
        if (
            $karyawan->pengguna->role === 'admin'
        ) {
        
            abort(403);
        }

        DB::beginTransaction();

        try {
            $newPassword = 'Tmp-' . strtoupper(Str::random(5));

            $karyawan->pengguna->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true,
                'remember_token' => Str::random(60)
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