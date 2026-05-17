<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        // alidasi 
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // cari
        $user = Pengguna::where('username', $request->username)->first();

        
        // user ga ditemukan
        if (!$user) {
            return back()
                ->withErrors([
                    'username' => 'Username tidak ditemukan'
                ])
                ->withInput();
        }

        // akun nonaktif
        if (!$user->is_active) {
            return back()
                ->withErrors([
                    'username' => 'Akun tidak aktif'
                ]);
        }

        // password salah
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'password' => 'Password salah'
                ])
                ->withInput();
        }

        // login
        Auth::login($user, $request->remember ?? false);

        // regenerate session
        $request->session()->regenerate();
        session([
            'password_hash' => $user->password
        ]);

        // WAJIB GANTI PASSWORD
        if ($user->must_change_password) {
            return redirect()->route('password.change');
        }

        //valiasi karywan
        if (in_array($user->role, [
            'admin',
            'tenant_relation',
            'departemen'
        ])) {

            if (!$user->karyawan) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Data karyawan tidak ditemukan'
                ]);
            }
        }

        // REDIRECT
        if ($user->role === 'unit') {
            return redirect('/ajukanKeluhan');
        }

        if (in_array($user->role, [
            'admin',
            'tenant_relation',
            'departemen'
        ])) {

            return redirect('/dashboard');
        }

        return redirect('/');
    }

    // ================= FORM GANTI PASSWORD =================
    public function showChangeForm()
    {
        // BELUM LOGIN
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        //akun dinonaktifkan 
        if (!$user->is_active) {

            Auth::logout();
        
            request()->session()->invalidate();
        
            request()->session()->regenerateToken();
        
            return redirect('/')
                ->withErrors([
                    'username' =>
                        'Akun sudah tidak aktif'
                ]);
        }

        // password direset admin
        if (
            session('password_hash') !== $user->password
        ) {
        
            Auth::logout();
        
            request()->session()->invalidate();
        
            request()->session()->regenerateToken();
        
            return redirect('/')
                ->withErrors([
                    'username' =>
                        'Session telah berakhir, silakan login kembali'
                ]);
        }

        // SUDAH TIDAK WAJIB GANTI PASSWORD
        if (!$user->must_change_password) {

            if ($user->role === 'unit') {
                return redirect('/ajukanKeluhan');
            }

            if (in_array($user->role, [
                'admin',
                'tenant_relation',
                'departemen'
            ])) {

                return redirect('/dashboard');
            }

            return redirect('/');
        }

        return view('auth.gantiPassword');
    }

    // ================= GANTI PASSWORD =================
    public function change(Request $request)
    {
        // VALIDASI LOGIN
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // akun nonaktif
        if (!$user->is_active) {

            Auth::logout();
        
            request()->session()->invalidate();
        
            request()->session()->regenerateToken();
        
            return redirect('/')
                ->withErrors([
                    'username' =>
                        'Akun sudah tidak aktif'
                ]);
        }

        // VALIDASI PASSWORD
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ]
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf besar dan angka',
        ]);

        // PASSWORD BARU TIDAK BOLEH SAMA
        if (Hash::check($request->password, $user->password)) {

            return back()->withErrors([
                'password' => 'Password baru tidak boleh sama dengan password lama'
            ]);
        }

        // UPDATE PASSWORD
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        session([
            'password_hash' => $user->fresh()->password
        ]);

        // REDIRECT
        if ($user->role === 'unit') {

            return redirect('/ajukanKeluhan')
                ->with('success', 'Password berhasil diubah');
        }

        if (in_array($user->role, [
            'admin',
            'tenant_relation',
            'departemen'
        ])) {

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