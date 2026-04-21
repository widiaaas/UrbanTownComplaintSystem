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

    // ================= SHOW FORM =================
    public function showChangeForm()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
    
        // 🔒 kalau sudah tidak wajib ganti password → jangan boleh akses
        if (!Auth::user()->must_change_password) {
            return redirect('/dashboard');
        }
    
        return view('auth.gantiPassword');
    }

    // ================= GANTI PASSWORD =================
    // ================= GANTI PASSWORD =================
    public function change(Request $request)
    {
        // 🔒 pastikan user login
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // 🔥 VALIDASI KUAT (tanpa JS)
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[A-Z]/',      // minimal 1 huruf besar
                'regex:/[0-9]/',      // minimal 1 angka
            ]
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf besar dan angka'
        ]);

        // 🔥 CEK PASSWORD LAMA (tidak boleh sama)
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password baru tidak boleh sama dengan password lama'
            ]);
        }

        // 🔥 UPDATE PASSWORD
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
            'last_login' => now()
        ]);

        // 🔥 REDIRECT BERDASARKAN ROLE
        if ($user->role === 'unit') {
            return redirect('/ajukanKeluhan')
                ->with('success', 'Password berhasil diubah');
        }

        if ($user->role === 'karyawan') {
            return redirect('/dashboard')
                ->with('success', 'Password berhasil diubah');
        }

        return redirect('/')
            ->with('success', 'Password berhasil diubah');
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