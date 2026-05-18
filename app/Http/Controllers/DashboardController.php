<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Karyawan;
use App\Models\Penghuni;
use App\Models\Unit;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // WAJIB GANTI PASSWORD
        if ($user->must_change_password) {
            return redirect()->route('password.change');
        }

        $karyawan = $user->karyawan;

        // VALIDASI KARYAWAN
        if (!$karyawan) {
            abort(403, 'Data karyawan tidak ditemukan');
        }

        $role = $user->role;

        /**
         * =========================================================
         * ADMIN
         * =========================================================
         */
        if ($role === 'admin') {

            $stats = [

                'unit' => Unit::where(
                    'status',
                    'Aktif'
                )->count(),

                'penghuni' => Penghuni::where(
                    'status',
                    'Aktif'
                )->count(),

                'karyawan' => Karyawan::where(
                    'status',
                    'Aktif'
                )->count(),
            ];

            return view('admin.dashboard', compact(
                'stats',
                'karyawan'
            ));
        }

        /**
         * =========================================================
         * TENANT RELATION
         * =========================================================
         */
        if ($role === 'tenant_relation') {

            $karyawanId = $karyawan->id;

            // QUERY KELUHAN
            $keluhanQuery = Keluhan::where(
                'penanggung_jawab_id',
                $karyawanId
            );

            // QUERY WORK ORDER
            $woQuery = WorkOrder::whereHas(
                'keluhan',
                function ($q) use ($karyawanId) {

                    $q->where(
                        'penanggung_jawab_id',
                        $karyawanId
                    );
                }
            );

            /**
             * ================= STATISTIK KELUHAN =================
             */
            $statsKeluhan = [

                'open' => (clone $keluhanQuery)
                    ->where('status', 'open')
                    ->count(),

                'on_progress' => (clone $keluhanQuery)
                    ->where('status', 'on_progress')
                    ->count(),

                'close' => (clone $keluhanQuery)
                    ->where('status', 'close')
                    ->count(),
            ];

            /**
             * ================= STATISTIK WORK ORDER =================
             */
            $statsWO = [

                'open' => (clone $woQuery)
                    ->where('status', 'open')
                    ->count(),

                'on_progress' => (clone $woQuery)
                    ->where('status', 'on_progress')
                    ->count(),

                'waiting' => (clone $woQuery)
                    ->where('status', 'waiting')
                    ->count(),

                'close' => (clone $woQuery)
                    ->where('status', 'close')
                    ->count(),
            ];

            /**
             * ================= TOTAL KELUHAN MASUK =================
             */
            $totalKeluhanMasuk = Keluhan::whereNull(
                'penanggung_jawab_id'
            )->count();

            /**
             * ================= SUMMARY =================
             */
            $totalKeluhan = array_sum(
                $statsKeluhan
            );

            $belumSelesai =
                $statsKeluhan['open'] +
                $statsKeluhan['on_progress'];

            $progressKeluhan = $totalKeluhan > 0
                ? ($statsKeluhan['close'] / $totalKeluhan) * 100
                : 0;

            /**
             * ================= DATA TERBARU =================
             */
            $recentKeluhan = (clone $keluhanQuery)

                ->with([
                    'unit',
                    'penghuni'
                ])

                ->latest()

                ->take(3)

                ->get();

            return view('tenantrelation.dashboard', compact(
                'statsKeluhan',
                'statsWO',
                'totalKeluhanMasuk',
                'totalKeluhan',
                'belumSelesai',
                'progressKeluhan',
                'recentKeluhan',
                'karyawan'
            ));
        }

        /**
         * =========================================================
         * DEPARTEMEN
         * =========================================================
         */
        if ($role === 'departemen') {

            $karyawanId = $karyawan->id;

            $query = WorkOrder::where(
                'penanggung_jawab_id',
                $karyawanId
            );

            /**
             * ================= STATISTIK =================
             */
            $statsWO = [

                'total' => (clone $query)
                    ->count(),

                'open' => (clone $query)
                    ->where('status', 'open')
                    ->count(),

                'on_progress' => (clone $query)
                    ->where('status', 'on_progress')
                    ->count(),

                'waiting' => (clone $query)
                    ->where('status', 'waiting')
                    ->count(),

                'close' => (clone $query)
                    ->where('status', 'close')
                    ->count(),
            ];

            /**
             * ================= TOTAL WO MASUK =================
             */
            $totalWOMasuk = WorkOrder::whereNull(
                'penanggung_jawab_id'
            )
        
            ->where(
                'departemen_id',
                $karyawan->departemen_id
            )
        
            ->count();

            /**
             * ================= OVERDUE =================
             */
            $overdue = (clone $query)

                ->where('status', '!=', 'close')

                ->where(
                    'created_at',
                    '<',
                    now()->subDays(2)
                )

                ->count();

            /**
             * ================= PROGRESS =================
             */
            $progress = $statsWO['total'] > 0
                ? ($statsWO['close'] / $statsWO['total']) * 100
                : 0;

            /**
             * ================= DATA TERBARU =================
             */
            $recentWO = (clone $query)

                ->with([
                    'keluhan',
                    'departemen'
                ])

                ->latest()

                ->take(5)

                ->get();

            return view('departemen.dashboard', compact(
                'statsWO',
                'totalWOMasuk',
                'overdue',
                'progress',
                'recentWO',
                'karyawan'
            ));
        }

        abort(403, 'Role tidak valid');
    }
}