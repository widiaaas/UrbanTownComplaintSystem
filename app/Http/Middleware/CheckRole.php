<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // =====================
        // ROLE UNIT
        // =====================
        if ($user->role === 'unit') {
            if (in_array('unit', $roles)) {
                return $next($request);
            }
        }

        // =====================
        // ROLE KARYAWAN
        // =====================
        if ($user->role === 'karyawan') {

            $karyawan = $user->karyawan;

            if (!$karyawan) {
                abort(403, 'Data karyawan tidak ditemukan');
            }

            if (in_array($karyawan->role, $roles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}