<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\PenghuniController;

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

    // --- Ubah password (wajib saat first login) ---
    Route::get('/change-password', [AuthController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'change']);

    // ---------- ADMIN ----------
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboardAdmin', fn() => view('admin.dashboard'));
        Route::get('/profileAdmin', fn() => view('admin.profile'));
        
        // Unit management
        Route::get('/IndexUnits', [App\Http\Controllers\Admin\UnitController::class, 'index'])->name('admin.units.index');
        Route::post('/units', [App\Http\Controllers\Admin\UnitController::class, 'store'])->name('admin.units.store');
        Route::put('/units/{unit}', [App\Http\Controllers\Admin\UnitController::class, 'update'])->name('admin.units.update');
        Route::delete('/units/{unit}', [App\Http\Controllers\Admin\UnitController::class, 'destroy'])->name('admin.units.destroy');
        Route::post('/units/{unit}/change-occupant', [App\Http\Controllers\Admin\UnitController::class, 'changeOccupant'])->name('admin.units.changeOccupant');
        Route::post('/units/{unit}/reset-password', [App\Http\Controllers\Admin\UnitController::class, 'resetPassword'])->name('admin.units.resetPassword');
        Route::post('/units/{unit}/toggle-status', [App\Http\Controllers\Admin\UnitController::class, 'toggleStatus'])->name('admin.units.toggleStatus');
        Route::get('/penghuni-available', [App\Http\Controllers\Admin\UnitController::class, 'getAvailablePenghuni'])->name('admin.penghuni.available');
        
        // Penghuni Management
        Route::get('/IndexPenghuni', [App\Http\Controllers\PenghuniController::class, 'index'])->name('admin.penghuni.index');
        Route::get('/penghuni', [PenghuniController::class, 'index'])->name('admin.penghuni.index');
        Route::post('/penghuni', [PenghuniController::class, 'store'])->name('admin.penghuni.store');
        Route::put('/penghuni/{penghuni}', [PenghuniController::class, 'update'])->name('admin.penghuni.update');
        Route::delete('/penghuni/{penghuni}', [PenghuniController::class, 'destroy'])->name('admin.penghuni.destroy');
        Route::get('/penghuni/{penghuni}/show', [PenghuniController::class, 'show'])->name('admin.penghuni.show');
        
        Route::get('/IndexKaryawan', fn() => view('admin.karyawan.index'));
    });

    // ---------- TENANT RELATION ----------
    Route::middleware(['role:tenant_relation'])->group(function () {
        Route::get('/dashboardTenantRelation', fn() => view('tenantrelation.dashboard'));
        Route::get('/profileTR', fn() => view('tenantrelation.profile'));
        Route::get('/keluhanMasuk', fn() => view('tenantRelation.keluhan.keluhanMasuk'));
        Route::get('/daftarPenanganan', fn() => view('tenantRelation.keluhan.daftarPenanganan'));
        Route::get('/detailKeluhan', fn() => view('tenantRelation.keluhan.detailKeluhan'));
        Route::get('/rekapPenangananTR', fn() => view('tenantRelation.laporan.rekapPenanganan'));
        Route::get('/knowledgeBase', fn() => view('tenantRelation.knowledgeBase.index'));
    });

    // ---------- DEPARTEMEN ----------
    Route::middleware(['role:departemen'])->group(function () {
        Route::get('/dashboardDepartemen', fn() => view('departemen.dashboard'));
        Route::get('/profileDepartemen', fn() => view('departemen.profile'));
        Route::get('/workOrderMasuk', fn() => view('departemen.workOrder.workOrderMasuk'));
        Route::get('/daftarWorkOrder', fn() => view('departemen.workOrder.daftarPenangananWO'));
        Route::get('/detailWorkOrder', fn() => view('departemen.workOrder.detailWorkOrder'));
    });

    // ---------- PENGHUNI (UNIT) ----------
    Route::middleware(['role:unit'])->group(function () {
        Route::get('/dashboardPenghuni', fn() => view('penghuni.dashboard'));
        Route::get('/profilePenghuni', fn() => view('penghuni.profile'));
        Route::get('/ajukanKeluhan', fn() => view('penghuni.ajukanKeluhan'));
        Route::get('/riwayatKeluhan', fn() => view('penghuni.riwayatKeluhan'));
    });
});