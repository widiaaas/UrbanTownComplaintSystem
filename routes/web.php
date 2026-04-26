<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\PenghuniController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\RiwayatPenangananWOController;
use App\Http\Controllers\RiwayatPenangananKeluhanController;
use App\Http\Controllers\KnowledgeBaseController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// // Welcome Page
// Route::get('/login', function () {
//     return view('auth.login');
// });

// Route::get('/dashboardTenantRelation', function () {
//     return view('tenantrelation.dashboard');
// });

// Route::get('/dashboardDepartemen', function (){
//     return view ('departemen.dashboard');
// });


// // ---------- ADMIN -------------------
// Route::get('/dashboardAdmin', function (){
//     return view ('admin.dashboard');
// });
// // Profile
// Route::get('/profileAdmin', function (){
//     return view ('admin.profile');
// });
// // Unit
// Route::get('/IndexUnits', function (){
//     return view ('admin.units.index');
// });
// Route::get('/CreateUnits', function (){
//     return view ('admin.units.create');
// });
// Route::get('/ShowUnits', function (){
//     return view ('admin.units.show');
// });

// //Penghuni
// Route::get('/IndexPenghuni', function (){
//     return view ('admin.penghuni.index');
// });

// // Karyawan
// Route::get('/IndexKaryawan', function (){
//     return view ('admin.karyawan.index');
// });



// // ---------- PENGHUNI -------------------
// Route::get('/dashboardPenghuni', function () {
//     return view('penghuni.dashboard');
// });

// //Profile 
// Route::get('/profilePenghuni', function (){
//     return view ('penghuni.profile');
// });

// // Pengajuan keluhan
// Route::get('/ajukanKeluhan', function (){
//     return view ('penghuni.ajukanKeluhan');
// });

// // Riwayat keluhan
// Route::get('/riwayatKeluhan', function (){
//     return view ('penghuni.riwayatKeluhan');
// });



// // ---------- TENANT RELATTION -------------------   
// // Keluhan
// Route::get('/keluhanMasuk', function (){
//     return view ('tenantRelation.keluhan.keluhanMasuk');
// });
// Route::get('/daftarPenanganan', function (){
//     return view ('tenantRelation.keluhan.daftarPenanganan');
// });
// Route::get('/detailKeluhan', function (){
//     return view ('tenantRelation.keluhan.detailKeluhan');
// });
// Route::get('/rekapPenangananTR', function (){
//     return view ('tenantRelation.laporan.rekapPenanganan');
// });
// Route::get('/profileTR', function (){
//     return view ('tenantRelation.profile');
// });
// Route::get('/knowledgeBase', function (){
//     return view ('tenantRelation.knowledgeBase.index');
// });

// // ---------- DEPARTEMEN -------------------   
// // Work Order
// Route::get('/workOrderMasuk', function (){
//     return view ('departemen.workOrder.workOrderMasuk');
// });
// Route::get('/daftarWorkOrder', function (){
//     return view ('departemen.workOrder.daftarPenangananWO');
// });
// Route::get('/detailWorkOrder', function (){
//     return view ('departemen.workOrder.detailWorkOrder');
// });
// Route::get('/profileDepartemen', function (){
//     return view ('departemen.profile');
// });


/// NEWW

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/tesKB', function () {
    return view('tesKB');
});
// ==================== AUTHENTICATED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // --- GantiPassword ---
    Route::get('/ganti-password', [AuthController::class, 'showChangeForm'])->name('password.change');
    Route::post('/ganti-password', [AuthController::class, 'change']);

    // ================= PROFILE UNIVERSAL =================
    Route::get('/profile', [ProfileController::class, 'index']); // view
    Route::get('/profile/me', [ProfileController::class, 'me']); // JSON API
    Route::put('/profile/update', [ProfileController::class, 'update']);
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ==================== ADMIN ====================
    Route::middleware(['role:admin'])->group(function () {
        
        // ================= UNIT =================
        Route::get('/IndexUnits', [UnitController::class, 'index'])->name('admin.units.index');
        Route::post('/units', [UnitController::class, 'store'])->name('admin.units.store');
        Route::put('/units/{unit}', [UnitController::class, 'update'])->name('admin.units.update');
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('admin.units.destroy');

        Route::post('/units/{unit}/reset-password', [UnitController::class, 'resetPassword'])->name('admin.units.resetPassword');
        Route::post('/units/{unit}/ganti-penghuni', [UnitController::class, 'gantiPenghuni'])->name('admin.units.gantiPenghuni');
        Route::put('/units/{unit}/toggle', [UnitController::class, 'toggleStatus'])->name('admin.units.toggleStatus');
        Route::get('/penghuni-available', [UnitController::class, 'getAvailablePenghuni'])->name('admin.penghuni.available');

        // ================= PENGHUNI =================
        Route::get('/IndexPenghuni', [PenghuniController::class, 'index'])->name('admin.penghuni.index');
        Route::post('/penghuni/store', [PenghuniController::class, 'store'])->name('admin.penghuni.store');
        Route::put('/penghuni/update/{penghuni}', [PenghuniController::class, 'update'])->name('admin.penghuni.update');
        Route::delete('/penghuni/delete/{penghuni}', [PenghuniController::class, 'destroy'])->name('admin.penghuni.destroy');
        Route::get('/penghuni/{penghuni}/show', [PenghuniController::class, 'show'])->name('admin.penghuni.show');

        // ================= KARYAWAN =================
        Route::get('/IndexKaryawan', [KaryawanController::class, 'index'])->name('admin.karyawan.index');
        Route::post('/karyawan', [KaryawanController::class, 'store'])->name('admin.karyawan.store');
        Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('admin.karyawan.update');
        Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('admin.karyawan.destroy');
        Route::post('/karyawan/{karyawan}/reset-password', [KaryawanController::class, 'resetPassword'])->name('admin.karyawan.resetPassword');
    });

    // ================= TENANT RELATION =================
    // ================= TENANT RELATION =================
    Route::middleware(['role:tenant_relation'])->group(function () {

        Route::get('/dashboardTenantRelation', [DashboardController::class, 'tenantRelation']);

        // 🔥 KELUHAN
        Route::get('/keluhan-masuk', [KeluhanController::class, 'keluhanMasuk'])->name('tr.keluhan.masuk');
        Route::post('/keluhan/{id}/ambil', [KeluhanController::class, 'ambilKeluhan'])->name('tr.keluhan.ambil');

        // 🔥 DAFTAR PENANGANAN
        Route::get('/daftar-penanganan', [KeluhanController::class, 'daftarPenanganan'])->name('tr.penanganan');

        // 🔥 DETAIL
        Route::get('/keluhan/{id}', [KeluhanController::class, 'show'])->name('tr.keluhan.detail');

        // 🔥 UPDATE STATUS (WAJIB)
        Route::post('/keluhan/{id}/status', [KeluhanController::class, 'updateStatus']);

        // Penanganan 

        Route::post('/keluhan/{id}/penanganan', [RiwayatPenangananKeluhanController::class, 'simpanPenanganan']);

        // 🔥 KEPUTUSAN AKHIR
        Route::post('/keluhan/{id}/keputusan-akhir', [KeluhanController::class, 'keputusanAkhir']);

        // WORK ORDER
        Route::post('/keluhan/{id}/work-order', [WorkOrderController::class, 'store']);
        

        // 🔥 LAPORAN
        Route::get('/rekap-penanganan', [KeluhanController::class, 'rekap'])->name('tr.rekap');

       // 🔥 KNOWLEDGE BASE - halaman view
        Route::get('/knowledge-base', [KnowledgeBaseController::class, 'page']);
        
        // 🔥 KNOWLEDGE BASE - API (untuk JS fetch)
        Route::get('/knowledge-base/list', [KnowledgeBaseController::class, 'index']);
        Route::post('/knowledge-base', [KnowledgeBaseController::class, 'store']);
        Route::get('/knowledge-base/search', [KnowledgeBaseController::class, 'search']);
        
        // 🔥 CRUD Diagnosis
        Route::post('/knowledge-base/{kb}/diagnosis', [KnowledgeBaseController::class, 'storeDiagnosis']);
        Route::post('/knowledge-base/{kb}/diagnosis/{diagnosis}', [KnowledgeBaseController::class, 'updateDiagnosis']);
        Route::delete('/knowledge-base/{kb}/diagnosis/{diagnosis}', [KnowledgeBaseController::class, 'destroyDiagnosis']);
        
        // 🔥 KB CRUD
        Route::put('/knowledge-base/{id}', [KnowledgeBaseController::class, 'update']);
        Route::delete('/knowledge-base/{id}', [KnowledgeBaseController::class, 'destroy']);
    });

    // ================= DEPARTEMEN =================
    Route::middleware(['role:departemen'])->group(function () {
        Route::get('/dashboardDepartemen', fn() => view('departemen.dashboard'));
        Route::get('/work-order-masuk', [WorkOrderController::class, 'woMasuk']);
        Route::post('/work-order/{id}/ambil', [WorkOrderController::class, 'ambilWO']);
        Route::get('/daftar-work-order', [WorkOrderController::class, 'daftarPenanganan']);
        Route::get('/detailWorkOrder/{id}', [WorkOrderController::class, 'detail']);
        Route::post('/work-order/{id}/status', [WorkOrderController::class, 'updateStatus']);

        Route::post('/work-order/{id}/penanganan', [RiwayatPenangananWOController::class, 'simpanPenanganan']);

        
    });


    Route::middleware(['role:unit'])->group(function () {

        Route::get('/ajukanKeluhan', [KeluhanController::class, 'index']);
        Route::post('/keluhan', [KeluhanController::class, 'store']);
        Route::get('/riwayatKeluhan', [KeluhanController::class, 'riwayat']);
    });

});