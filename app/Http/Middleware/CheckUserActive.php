<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    public function handle(
        Request $request,
        Closure $next
    ) {

        if (Auth::check()) {

            $user = Auth::user();

            // ================= AKUN NONAKTIF =================
            if (!$user->is_active) {

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect('/')
                    ->withErrors([
                        'username' =>
                            'Akun sudah tidak aktif'
                    ]);
            }

            // ================= PASSWORD DIRESET =================
            if (
                session('password_hash') !==
                $user->password
            ) {

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect('/')
                    ->withErrors([
                        'username' =>
                            'Password telah direset, silakan login kembali'
                    ]);
            }
        }

        return $next($request);
    }
}