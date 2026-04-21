<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Penghuni;
use App\Models\Karyawan;
use App\Models\Keluhan;
use App\Models\WorkOrder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $karyawan = $user->karyawan;

        if (auth()->user()->must_change_password) {
            return redirect()->route('password.change');
        }

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

                $userId = $karyawan->id;
            
                // 🔹 FILTER BERDASARKAN PENANGGUNG JAWAB
                $keluhanQuery = Keluhan::where('penanggung_jawab_id', $userId);
                $woQuery = WorkOrder::where('penanggung_jawab_id', $userId);
            
                // ================= STATISTIK KELUHAN =================
                $statsKeluhan = [
                    'open' => (clone $keluhanQuery)->where('status', 'open')->count(),
                    'on_progress' => (clone $keluhanQuery)->where('status', 'on_progress')->count(),
                    'close' => (clone $keluhanQuery)->where('status', 'close')->count(),
                ];
            
                // ================= STATISTIK WO =================
                $statsWO = [
                    'open' => (clone $woQuery)->where('status', 'open')->count(),
                    'on_progress' => (clone $woQuery)->where('status', 'on_progress')->count(),
                    'waiting' => (clone $woQuery)->where('status', 'waiting')->count(),
                    'close' => (clone $woQuery)->where('status', 'close')->count(),
                ];
            
                // ================= TOTAL KELUHAN MASUK =================
                // = WO yang belum ditangani
                $totalKeluhanMasuk = (clone $woQuery)
                    ->where('status', 'unassigned')
                    ->count();
            
                // ================= SUMMARY =================
                $totalKeluhanMasuk = Keluhan::whereNull('penanggung_jawab_id')->count();

                $totalKeluhan = array_sum($statsKeluhan);

                $belumSelesai = $statsKeluhan['open'] + $statsKeluhan['on_progress'];

                $progressKeluhan = $totalKeluhan > 0
                    ? ($statsKeluhan['close'] / $totalKeluhan) * 100
                    : 0;
            
                // ================= KELUHAN TERBARU =================
                $recentKeluhan = (clone $keluhanQuery)
                    ->latest()
                    ->take(5)
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



            // ================= DEPARTEMEN =================
            case 'departemen':

                $userId = $karyawan->id;
            
                // 🔹 FILTER BERDASARKAN PENANGGUNG JAWAB
                $query = WorkOrder::where('penanggung_jawab_id', $userId);
            
                // ================= STATISTIK =================
                $statsWO = [
                    'total' => (clone $query)->count(),
                    'open' => (clone $query)->where('status','open')->count(),
                    'on_progress' => (clone $query)->where('status','on_progress')->count(),
                    'waiting' => (clone $query)->where('status','waiting')->count(),
                    'close' => (clone $query)->where('status','close')->count(),
                ];
            
                // ================= TOTAL MASUK =================
                $totalWOMasuk = (clone $query)
                    ->where('status', 'unassigned')
                    ->count();
            
                // ================= OVERDUE =================
                $overdue = (clone $query)
                    ->where('status','!=','close')
                    ->where('created_at', '<', now()->subDays(2))
                    ->count();
            
                // ================= PROGRESS =================
                $progress = $statsWO['total'] > 0
                    ? ($statsWO['close'] / $statsWO['total']) * 100
                    : 0;
            
                // ================= TERBARU =================
                $recentWO = (clone $query)->latest()->take(5)->get();
            
                return view('departemen.dashboard', compact(
                    'statsWO',
                    'totalWOMasuk',
                    'overdue',
                    'progress',
                    'recentWO',
                    'karyawan'
                ));


            default:
                abort(403);
        }
    }
}