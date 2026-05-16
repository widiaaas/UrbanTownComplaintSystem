<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ) {

        /**
         * =====================================================
         * BELUM LOGIN
         * =====================================================
         */
        if (!Auth::check()) {

            return redirect()->route('login');
        }

        $user = Auth::user();

        /**
         * =====================================================
         * VALIDASI ROLE
         * =====================================================
         */
        if (!in_array($user->role, $roles)) {

            abort(403, 'Unauthorized');
        }

        /**
         * =====================================================
         * VALIDASI DATA KARYAWAN
         * =====================================================
         * Khusus:
         * admin
         * tenant_relation
         * departemen
         */
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

        /**
         * =====================================================
         * VALIDASI DEPARTEMEN
         * =====================================================
         * Role departemen wajib memiliki departemen
         */
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