<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ================= PROFILE PAGE =================
    public function profile()
    {
        $user = auth()->user();

        return view('admin.profile', compact('user'));
    }

    // ================= UPDATE PROFILE =================
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'nama' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],
            'email' => [
                'required',
                'max:100',
                'unique:penggunas,email,' . $user->id,
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'
            ],
            'telp' => ['required','regex:/^08[0-9]{8,11}$/'],
            'departemen' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ], [
            'nama.regex' => 'Nama hanya boleh huruf dan spasi',
            'telp.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit',
            'email.regex' => 'Format email harus benar (contoh: nama@gmail.com)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }

    // ================= UPDATE PASSWORD =================
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ], [
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // cek password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'password_lama' => ['Password lama salah']
                ]
            ], 422);
        }

        // update password
        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
}