<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Penghuni;
use App\Models\Karyawan;
use App\Models\Keluhan;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            abort(403, 'Data karyawan tidak ditemukan');
        }

        switch ($karyawan->role) {

            // ================= ADMIN =================
            case 'admin':

                $stats = [
                    'unit' => Unit::where('status','Aktif')->count(),
                    'penghuni' => Penghuni::where('status','Aktif')->count(),
                    'karyawan' => Karyawan::count(),
                ];

                return view('admin.dashboard', compact('stats', 'karyawan'));

            // ================= TENANT RELATION =================
            case 'tenant_relation':

                // ================= KELUHAN =================
                $statsKeluhan = [
                    'open' => Keluhan::where('status', 'open')->count(),
                    'on_progress' => Keluhan::where('status', 'on_progress')->count(),
                    'close' => Keluhan::where('status', 'close')->count(),
                ];
            
                // ================= WORK ORDER =================
                $statsWO = [
                    'open' => WorkOrder::where('status', 'open')->count(),
                    'on_progress' => WorkOrder::where('status', 'on_progress')->count(),
                    'waiting' => WorkOrder::where('status', 'waiting')->count(),
                    'close' => WorkOrder::where('status', 'close')->count(),
                ];
            
                return view('tenantrelation.dashboard', [
                    'statsKeluhan' => $statsKeluhan,
                    'statsWO' => $statsWO,
                    'karyawan' => $karyawan
                ]);
                
            // ================= DEPARTEMEN =================
            case 'departemen':

                $statsWO = [
                    'total' => WorkOrder::count(),
                    'open' => WorkOrder::where('status','open')->count(),
                    'on_progress' => WorkOrder::where('status','on_progress')->count(),
                    'waiting' => WorkOrder::where('status','waiting')->count(),
                    'close' => WorkOrder::where('status','close')->count(),
                ];

                return view('departemen.dashboard', compact('statsWO','karyawan'));

            default:
                abort(403);
        }
    }
}