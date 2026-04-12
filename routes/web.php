<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\PenghuniController;


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

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // --- Change Password ---
    Route::get('/change-password', [AuthController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'change']);

    // ==================== ADMIN ====================
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/dashboardAdmin', fn() => view('admin.dashboard'));
        
        // PROFLE 
        Route::get('/profileAdmin', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profileAdmin/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        Route::put('/profileAdmin/update-password', [AdminController::class, 'updatePassword'])->name('admin.profile.updatePassword');

        // ================= UNIT =================
        Route::get('/IndexUnits', [UnitController::class, 'index'])->name('admin.units.index');
        Route::post('/units', [UnitController::class, 'store'])->name('admin.units.store');
        Route::put('/units/{unit}', [UnitController::class, 'update'])->name('admin.units.update');
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('admin.units.destroy');

        Route::post('/units/{unit}/reset-password', [UnitController::class, 'resetPassword'])->name('admin.units.resetPassword');
        Route::post('/units/{unit}/change-occupant', [UnitController::class, 'changeOccupant'])->name('admin.units.changeOccupant');
        Route::post('/units/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('admin.units.toggleStatus');
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
    Route::middleware(['role:tenant_relation'])->group(function () {
        Route::get('/dashboardTenantRelation', fn() => view('tenantrelation.dashboard'));
        Route::get('/profileTR', fn() => view('tenantrelation.profile'));
        Route::get('/keluhanMasuk', fn() => view('tenantRelation.keluhan.keluhanMasuk'));
        Route::get('/daftarPenanganan', fn() => view('tenantRelation.keluhan.daftarPenanganan'));
        Route::get('/detailKeluhan', fn() => view('tenantRelation.keluhan.detailKeluhan'));
        Route::get('/rekapPenangananTR', fn() => view('tenantRelation.laporan.rekapPenanganan'));
        Route::get('/knowledgeBase', fn() => view('tenantRelation.knowledgeBase.index'));
    });

    // ================= DEPARTEMEN =================
    Route::middleware(['role:departemen'])->group(function () {
        Route::get('/dashboardDepartemen', fn() => view('departemen.dashboard'));
        Route::get('/profileDepartemen', fn() => view('departemen.profile'));
        Route::get('/workOrderMasuk', fn() => view('departemen.workOrder.workOrderMasuk'));
        Route::get('/daftarWorkOrder', fn() => view('departemen.workOrder.daftarPenangananWO'));
        Route::get('/detailWorkOrder', fn() => view('departemen.workOrder.detailWorkOrder'));
    });

    // ================= PENGHUNI =================
    Route::middleware(['role:unit'])->group(function () {
        Route::get('/dashboardPenghuni', fn() => view('penghuni.dashboard'));
        Route::get('/profilePenghuni', fn() => view('penghuni.profile'));
        Route::get('/ajukanKeluhan', fn() => view('penghuni.ajukanKeluhan'));
        Route::get('/riwayatKeluhan', fn() => view('penghuni.riwayatKeluhan'));
    });
});