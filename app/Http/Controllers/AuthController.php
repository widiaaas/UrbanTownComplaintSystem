<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    // TAMPILKAN FORM LOGIN
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // CARI USER
        $user = Pengguna::where('username', $request->username)->first();

        // CEK USER
        if (!$user) {
            return back()
                ->withErrors(['username' => 'Username tidak ditemukan'])
                ->withInput();
        }

        // CEK STATUS AKTIF
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

    // UPDATE LAST LOGIN
    $user->update([
        'last_login' => now()
    ]);

    // CEK HARUS GANTI PASSWORD
    if ($user->must_change_password) {
        return redirect()->route('change.password');
    }

    // ================= ROLE LOGIC =================

    // JIKA UNIT
    if ($user->role === 'unit') {
        return redirect('/dashboard/unit');
    }

    // JIKA KARYAWAN
    if ($user->role === 'karyawan') {

        // ambil data karyawan
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            Auth::logout();
            return back()->withErrors(['username' => 'Data karyawan tidak ditemukan']);
        }

        // ADMIN
        if ($karyawan->role === 'admin') {
            return redirect('/dashboardAdmin');
        }

        // TENANT RELATION
        if ($karyawan->role === 'tenant_relation') {
            return redirect('/dashboardTenantRelation');
        }

        // DEPARTEMEN
        if ($karyawan->role === 'departemen') {
            return redirect('/dashboardDepartemen');
        }

        // UNIT
        if ($user->role === 'unit') {
            return redirect('/dashboardPenghuni');
        }
    }

    // fallback
    return redirect('/');
    }
}