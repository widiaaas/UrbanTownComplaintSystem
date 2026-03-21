<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Page
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboardTenantRelation', function () {
    return view('tenantrelation.dashboard');
});

Route::get('/dashboardDepartemen', function (){
    return view ('departemen.dashboard');
});


// ---------- ADMIN -------------------
Route::get('/dashboardAdmin', function (){
    return view ('admin.dashboard');
});
// Profile
Route::get('/profileAdmin', function (){
    return view ('admin.profile');
});
// Unit
Route::get('/IndexUnits', function (){
    return view ('admin.units.index');
});
Route::get('/CreateUnits', function (){
    return view ('admin.units.create');
});
Route::get('/ShowUnits', function (){
    return view ('admin.units.show');
});

//Penghuni
Route::get('/IndexPenghuni', function (){
    return view ('admin.penghuni.index');
});

// Karyawan
Route::get('/IndexKaryawan', function (){
    return view ('admin.karyawan.index');
});



// ---------- PENGHUNI -------------------
Route::get('/dashboardPenghuni', function () {
    return view('penghuni.dashboard');
});

//Profile 
Route::get('/profilePenghuni', function (){
    return view ('penghuni.profile');
});

// Pengajuan keluhan
Route::get('/ajukanKeluhan', function (){
    return view ('penghuni.ajukanKeluhan');
});

// Riwayat keluhan
Route::get('/riwayatKeluhan', function (){
    return view ('penghuni.riwayatKeluhan');
});



// ---------- TENANT RELATTION -------------------   
// Keluhan
Route::get('/keluhanMasuk', function (){
    return view ('tenantRelation.keluhan.keluhanMasuk');
});
Route::get('/daftarPenanganan', function (){
    return view ('tenantRelation.keluhan.daftarPenanganan');
});
Route::get('/detailKeluhan', function (){
    return view ('tenantRelation.keluhan.detailKeluhan');
});
Route::get('/rekapPenangananTR', function (){
    return view ('tenantRelation.laporan.rekapPenanganan');
});
Route::get('/profileTR', function (){
    return view ('tenantRelation.profile');
});
Route::get('/knowledgeBase', function (){
    return view ('tenantRelation.knowledgeBase.index');
});

// ---------- DEPARTEMEN -------------------   
// Work Order
Route::get('/workOrderMasuk', function (){
    return view ('departemen.workOrder.workOrderMasuk');
});
Route::get('/daftarWorkOrder', function (){
    return view ('departemen.workOrder.daftarPenangananWO');
});
Route::get('/detailWorkOrder', function (){
    return view ('departemen.workOrder.detailWorkOrder');
});
Route::get('/profileDepartemen', function (){
    return view ('departemen.profile');
});
