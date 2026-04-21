<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{   
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'karyawan') {
            $profile = $user->karyawan;
        } else {
            $profile = $user->unit;
        }

        return view('profile', compact('user', 'profile'));
    }
    // ================= GET PROFILE =================
    public function me()
    {
        $user = auth()->user();

        // 🔥 BIARKAN KARYAWAN TETAP SEPERTI SEMULA
        $profile = match ($user->role) {
            'karyawan' => $user->karyawan,
            'unit' => $user->unit,
            default => null
        };

        // 🔥 KHUSUS UNIT → OVERRIDE
        if ($user->role === 'unit') {
            $unit = $user->unit;

            $profile = [
                'no_unit' => $unit?->no_unit,
                'penghuni' => $unit?->penghuniAktif
            ];
        }

        return response()->json([
            'user' => $user,
            'profile' => $profile,
            'options' => [
                'jenis_kelamin' => ['Laki-laki','Perempuan']
            ]
        ]);
    }

    // ================= UPDATE PROFILE =================
    public function update(Request $request)
    {
        $user = auth()->user();

        // ================= KARYAWAN =================
        if ($user->role === 'karyawan') {

            $karyawan = $user->karyawan;

            if (!$karyawan) {
                return response()->json([
                    'message' => 'Data karyawan tidak ditemukan'
                ], 404);
            }

            // 🔥 VALIDASI
            $validator = Validator::make($request->all(), [
                'nama' => [
                    'required',
                    'regex:/^[a-zA-Z\s]+$/',
                    'max:100'
                ],
                'email' => [
                    'required',
                    'email:rfc,dns',
                    'max:100',
                    Rule::unique('karyawans', 'email')->ignore($karyawan->id)
                ],
                'telp' => [
                    'required',
                    'regex:/^[0-9]{10,13}$/'
                ],
                'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            ], [
                // 🔥 CUSTOM MESSAGE
                'nama.required' => 'Nama wajib diisi',
                'nama.regex' => 'Nama hanya boleh huruf dan spasi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'telp.required' => 'Nomor telepon wajib diisi',
                'telp.regex' => 'Nomor telepon harus angka dan 10-13 digit',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // 🔥 UPDATE
            $karyawan->update([
                'nama' => trim($request->nama),
                'email' => trim($request->email),
                'telp' => trim($request->telp),
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        // ================= UNIT =================
        elseif ($user->role === 'unit') {

            $unit = $user->unit;
        
            if (!$unit) {
                return response()->json([
                    'message' => 'Data unit tidak ditemukan'
                ], 404);
            }
        
            // 🔥 ambil penghuni aktif
            $penghuni = $unit->penghuniAktif;
        
            if (!$penghuni) {
                return response()->json([
                    'message' => 'Tidak ada penghuni aktif pada unit ini'
                ], 404);
            }
        
            // 🔥 VALIDASI (SAMA SEPERTI KARYAWAN)
            $validator = Validator::make($request->all(), [
                'nama' => [
                    'required',
                    'regex:/^[a-zA-Z\s]+$/',
                    'max:100'
                ],
                'email' => [
                    'nullable',
                    'email:rfc',
                    'max:100',
                    Rule::unique('penghunis', 'email')->ignore($penghuni->id)
                ],
                'telp' => [
                    'required',
                    'regex:/^[0-9]{10,13}$/'
                ],
                'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            ], [
                'nama.required' => 'Nama wajib diisi',
                'nama.regex' => 'Nama hanya boleh huruf dan spasi',
        
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
        
                'telp.required' => 'Nomor telepon wajib diisi',
                'telp.regex' => 'Nomor telepon harus angka dan 10-13 digit',
        
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
        
            // 🔥 UPDATE KE PENGHUNI
            $penghuni->update([
                'nama' => trim($request->nama),
                'email' => $request->email ? trim($request->email) : null,
                'telepon' => trim($request->telp),
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        // 🔥 VALIDASI (SAMAKAN DENGAN change())
        $validator = Validator::make($request->all(), [
            'password_lama' => ['required'],
            'password_baru' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[A-Z]/', // huruf besar
                'regex:/[0-9]/', // angka
            ],
        ], [
            // 🔥 CUSTOM MESSAGE
            'password_lama.required' => 'Password lama wajib diisi',

            'password_baru.required' => 'Password baru wajib diisi',
            'password_baru.min' => 'Password minimal 6 karakter',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok',
            'password_baru.regex' => 'Password harus mengandung huruf besar dan angka',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // 🔥 CEK PASSWORD LAMA
        if (!Hash::check($request->password_lama, $user->password)) {
            return response()->json([
                'errors' => [
                    'password_lama' => ['Password lama tidak sesuai']
                ]
            ], 422);
        }

        // 🔥 CEK PASSWORD BARU TIDAK BOLEH SAMA
        if (Hash::check($request->password_baru, $user->password)) {
            return response()->json([
                'errors' => [
                    'password_baru' => ['Password baru tidak boleh sama dengan password lama']
                ]
            ], 422);
        }

        // 🔥 UPDATE PASSWORD
        $user->update([
            'password' => Hash::make(trim($request->password_baru)),
            'must_change_password' => false,
            'last_login' => now()
        ]);

        return response()->json([
            'message' => 'Password berhasil diubah'
        ]);
    }
}