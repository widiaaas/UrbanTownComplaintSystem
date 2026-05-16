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

       // user login tapi dah nonaktif
        if (
            Auth::check() &&
            !Auth::user()->is_active
        ) {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/')
                ->withErrors([
                    'username' =>
                        'Akun sudah tidak aktif'
                ]);
        }

        return $next($request);
    }
}