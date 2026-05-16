<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * ================= INDEX =================
     */
    public function index()
    {
        $user = auth()->user();

        /**
         * =====================================================
         * ROLE SYSTEM BARU
         * =====================================================
         */
        $profile = match ($user->role) {

            'admin',
            'tenant_relation',
            'departemen'
                => $user->karyawan,

            'unit' => $user->unit()
                ->with(
                    'penghuniAktif.penghuni'
                )
                ->first(),

            default => null
        };

        return view('profile', compact(
            'user',
            'profile'
        ));
    }

    /**
     * ================= SHOW PROFILE =================
     */
    public function show()
    {
        $user = auth()->user();

        /**
         * =====================================================
         * KARYAWAN
         * =====================================================
         */
        $profile = match ($user->role) {

            'admin',
            'tenant_relation',
            'departemen'
                => $user->karyawan,

            'unit'
                => $user->unit,

            default => null
        };

        /**
         * =====================================================
         * KHUSUS UNIT
         * =====================================================
         */
        if ($user->role === 'unit') {

            $unit = $user->unit;

            $profile = [

                'nomor_unit' =>
                    $unit?->nomor_unit,

                'penghuni' =>
                    $unit?->penghuniAktif?->penghuni
            ];
        }

        return response()->json([

            'user' => $user,

            'profile' => $profile,

            'options' => [

                'jenis_kelamin' => [
                    'Laki-laki',
                    'Perempuan'
                ]
            ]
        ]);
    }

    /**
     * ================= UPDATE PROFILE =================
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Karyawan
        if (
            in_array(
                $user->role,
                [
                    'admin',
                    'tenant_relation',
                    'departemen'
                ]
            )
        ) {

            $karyawan = $user->karyawan;

            /**
             * =================================================
             * VALIDASI RELASI
             * =================================================
             */
            if (!$karyawan) {

                return response()->json([

                    'message' =>
                        'Data karyawan tidak ditemukan'

                ], 404);
            }

            /**
             * =================================================
             * VALIDASI
             * =================================================
             */
            $validator = Validator::make($request->all(), [

                'nama' => [
                    'required',
                    'regex:/^[A-Za-z\s\.\']+$/',
                    'max:100'
                ],

                'email' => [

                    'required',

                    'email',

                    'max:100',

                    Rule::unique(
                        'karyawans',
                        'email'
                    )->ignore($karyawan->id)
                ],

                'no_telepon' => [
                    'required',
                    'regex:/^(08|\+628)[0-9]{8,11}$/'
                ],

                'jenis_kelamin' => [

                    'required',

                    Rule::in([
                        'Laki-laki',
                        'Perempuan'
                    ])
                ],

            ], [

                'nama.required' =>
                    'Nama wajib diisi',

                'nama.regex' =>
                    'Nama hanya boleh huruf, titik, apostrophe, dan spasi',

                'email.required' =>
                    'Email wajib diisi',

                'email.email' =>
                    'Format email tidak valid',

                'email.unique' =>
                    'Email sudah digunakan',

                'no_telepon.required' =>
                    'Nomor telepon wajib diisi',

                'no_telepon.regex' =>
                    'Nomor telepon tidak valid',

                'jenis_kelamin.required' =>
                    'Jenis kelamin wajib dipilih',
            ]);

            /**
             * =================================================
             * VALIDATION ERROR
             * =================================================
             */
            if ($validator->fails()) {

                return response()->json([

                    'errors' =>
                        $validator->errors()

                ], 422);
            }

            /**
             * =================================================
             * UPDATE KARYAWAN
             * =================================================
             */
            $karyawan->update([

                'nama' =>
                    trim($request->nama),

                'email' =>
                    strtolower(
                        trim($request->email)
                    ),

                'no_telepon' =>
                    trim($request->no_telepon),

                'jenis_kelamin' =>
                    $request->jenis_kelamin,
            ]);
        }

        /**
         * =====================================================
         * UNIT / PENGHUNI
         * =====================================================
         */
        elseif ($user->role === 'unit') {

            $unit = $user->unit;

            /**
             * =================================================
             * VALIDASI UNIT
             * =================================================
             */
            if (!$unit) {

                return response()->json([

                    'message' =>
                        'Data unit tidak ditemukan'

                ], 404);
            }

            /**
             * =================================================
             * AMBIL PENGHUNI AKTIF
             * =================================================
             */
            $riwayat = $unit->penghuniAktif;
            $penghuni = $riwayat?->penghuni;

            if (!$penghuni) {

                return response()->json([

                    'message' =>
                        'Tidak ada penghuni aktif pada unit ini'

                ], 404);
            }

            /**
             * =================================================
             * VALIDASI
             * =================================================
             */
            $validator = Validator::make($request->all(), [

                'nama' => [
                    'required',
                    'regex:/^[A-Za-z\s\.\']+$/',
                    'max:100'
                ],

                'email' => [

                    'nullable',

                    'email',

                    'max:100',

                    Rule::unique(
                        'penghunis',
                        'email'
                    )->ignore($penghuni->id)
                ],

                'telepon' => [
                    'required',
                    'regex:/^(08|\+628)[0-9]{8,11}$/'
                ],

                'jenis_kelamin' => [

                    'required',

                    Rule::in([
                        'Laki-laki',
                        'Perempuan'
                    ])
                ],

            ], [

                'nama.required' =>
                    'Nama wajib diisi',

                'nama.regex' =>
                    'Nama hanya boleh huruf, titik, apostrophe, dan spasi',

                'email.email' =>
                    'Format email tidak valid',

                'email.unique' =>
                    'Email sudah digunakan',

                'telepon.required' =>
                    'Nomor telepon wajib diisi',

                'telepon.regex' =>
                    'Nomor telepon tidak valid',

                'jenis_kelamin.required' =>
                    'Jenis kelamin wajib dipilih',
            ]);

            /**
             * =================================================
             * VALIDATION ERROR
             * =================================================
             */
            if ($validator->fails()) {

                return response()->json([

                    'errors' =>
                        $validator->errors()

                ], 422);
            }

            /**
             * =================================================
             * UPDATE PENGHUNI
             * =================================================
             */
            $penghuni->update([

                'nama' =>
                    trim($request->nama),

                'email' =>
                    $request->email
                        ? strtolower(
                            trim($request->email)
                        )
                        : null,

                'telepon' =>
                    trim($request->telepon),

                'jenis_kelamin' =>
                    $request->jenis_kelamin,
            ]);
        }

        return response()->json([

            'message' =>
                'Profil berhasil diperbarui'
        ]);
    }

    /**
     * ================= UPDATE PASSWORD =================
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        /**
         * =====================================================
         * VALIDASI
         * =====================================================
         */
        $validator = Validator::make($request->all(), [

            'password_lama' => [
                'required'
            ],

            'password_baru' => [

                'required',

                'string',

                'min:6',

                'confirmed',

                'regex:/[A-Z]/',

                'regex:/[0-9]/',
            ],

        ], [

            'password_lama.required' =>
                'Password lama wajib diisi',

            'password_baru.required' =>
                'Password baru wajib diisi',

            'password_baru.min' =>
                'Password minimal 6 karakter',

            'password_baru.confirmed' =>
                'Konfirmasi password tidak cocok',

            'password_baru.regex' =>
                'Password harus mengandung huruf besar dan angka',
        ]);

        /**
         * =====================================================
         * VALIDATION ERROR
         * =====================================================
         */
        if ($validator->fails()) {

            return response()->json([

                'errors' =>
                    $validator->errors()

            ], 422);
        }

        /**
         * =====================================================
         * PASSWORD LAMA SALAH
         * =====================================================
         */
        if (
            !Hash::check(
                $request->password_lama,
                $user->password
            )
        ) {

            return response()->json([

                'errors' => [

                    'password_lama' => [
                        'Password lama tidak sesuai'
                    ]
                ]

            ], 422);
        }

        /**
         * =====================================================
         * PASSWORD BARU SAMA
         * =====================================================
         */
        if (
            Hash::check(
                $request->password_baru,
                $user->password
            )
        ) {

            return response()->json([

                'errors' => [

                    'password_baru' => [
                        'Password baru tidak boleh sama dengan password lama'
                    ]
                ]

            ], 422);
        }

        /**
         * =====================================================
         * UPDATE PASSWORD
         * =====================================================
         */
        $user->update([

            'password' => Hash::make(
                trim($request->password_baru)
            ),

            'must_change_password' => false,
        ]);

        return response()->json([

            'message' =>
                'Password berhasil diubah'
        ]);
    }
}