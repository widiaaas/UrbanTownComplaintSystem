<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordReset
{
    public function handle(
        Request $request,
        Closure $next
    ) {

        if (Auth::check()) {

            $user = Auth::user();

            /**
             * SESSION LAMA
             */
            if (
                $user->must_change_password &&
                !$request->routeIs(
                    'password.change'
                )
            ) {

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect('/')
                    ->withErrors([
                        'username' =>
                            'Session berakhir, silakan login kembali'
                    ]);
            }
        }

        return $next($request);
    }
}