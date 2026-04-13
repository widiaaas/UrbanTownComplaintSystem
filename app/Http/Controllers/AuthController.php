<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    // ================= LOGIN FORM =================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // CARI USER
        $user = Pengguna::where('username', $request->username)->first();

        if (!$user) {
            return back()
                ->withErrors(['username' => 'Username tidak ditemukan'])
                ->withInput();
        }

        // CEK AKTIF
        if (!$user->is_active) {
            return back()
                ->withErrors(['username' => 'Akun tidak aktif']);
        }

        // CEK PASSWORD
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah'])
                ->withInput();
        }

        // LOGIN
        Auth::login($user, $request->remember ?? false);

        // SECURITY (WAJIB)
        $request->session()->regenerate();

        // UPDATE LAST LOGIN
        $user->update([
            'last_login' => now()
        ]);

        // HARUS GANTI PASSWORD
        if ($user->must_change_password) {
            return redirect()->route('password.change');
        }

        // ================= ROLE BASED REDIRECT =================

        // UNIT (TENANT)
        if ($user->role === 'unit') {
            return redirect('/ajukanKeluhan');
        }

        // KARYAWAN
        if ($user->role === 'karyawan') {

            $karyawan = $user->karyawan;

            if (!$karyawan) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Data karyawan tidak ditemukan'
                ]);
            }

            return redirect('/dashboard');
        }

        // fallback
        return redirect('/');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}