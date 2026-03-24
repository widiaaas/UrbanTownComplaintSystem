<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda dinonaktifkan.']);
            }

            $user->last_login = now();
            $user->save();

            // If password must be changed, redirect to change form
            if ($user->must_change_password) {
                return redirect()->route('password.change');
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show password change form.
     */
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    /**
     * Handle password change.
     */
    public function change(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password_hash = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        // Logout after password change to force re-login with new password
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }

    /**
     * Redirect to appropriate dashboard based on user role.
     * Menggunakan URL langsung (tidak bergantung route name) agar kompatibel dengan route yang ada.
     */
    protected function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'admin' => redirect('/dashboardAdmin'),
            'tenant_relation' => redirect('/dashboardTenantRelation'),
            'departemen' => redirect('/dashboardDepartemen'),
            'unit' => redirect('/dashboardPenghuni'),
            default => redirect('/'),
        };
    }
}