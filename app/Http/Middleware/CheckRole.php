<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle( Request $request, Closure $next,...$roles ) {

       // Belum login
        if (!Auth::check()) {

            return redirect()->route('login');
        }

        $user = Auth::user();
        

        // validasi role
        if (!in_array($user->role, $roles)) {

            abort(403, 'Unauthorized');
        }

       // Validasi karyawan
        if (
            in_array(
                $user->role,
                ['admin', 'tenant_relation', 'departemen']
            )
        ) {

            if (!$user->karyawan) {

                abort(
                    403,
                    'Data karyawan tidak ditemukan'
                );
            }
        }

        // validasi departemen
        if ($user->role === 'departemen') {

            if (
                !$user->karyawan->departemen_id
            ) {

                abort(
                    403,
                    'Departemen tidak ditemukan'
                );
            }
        }

        return $next($request);
    }
}