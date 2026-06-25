<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KRSController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    }
    
    return redirect()->route('dashboard.mahasiswa');
})->middleware(['auth', 'verified'])->name('dashboard');

// ============================================
// ROUTES UNTUK ADMIN (Full CRUD)
// ============================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');
    
    // ============================================
    // 1. DOSEN RESOURCE + EXPORT
    // ============================================
    Route::resource('dosen', DosenController::class);
    
    // TAMBAHKAN ROUTE EXPORT DI SINI (SEBELUM resource atau SESUDAH)
    Route::get('/dosen/export-pdf', [DosenController::class, 'exportPDF'])
        ->name('dosen.export-pdf');
    Route::get('/dosen/export-excel', [DosenController::class, 'exportExcel'])
        ->name('dosen.export-excel');
    Route::get('/dosen/search', [DosenController::class, 'search'])
        ->name('dosen.search');
    
    // ============================================
    // 2. MAHASISWA RESOURCE
    // ============================================
    Route::resource('mahasiswa', MahasiswaController::class);
    
    // ============================================
    // 3. MATA KULIAH RESOURCE
    // ============================================
    Route::resource('matakuliah', MataKuliahController::class);
    
    // ============================================
    // 4. JADWAL RESOURCE
    // ============================================
    Route::resource('jadwal', JadwalController::class);
    Route::get('/jadwal/api/get', [JadwalController::class, 'getJadwal'])
        ->name('jadwal.api.get');
    
    // ============================================
    // 5. KRS RESOURCE
    // ============================================
    Route::resource('krs', KRSController::class);
});

// ============================================
// ROUTES UNTUK MAHASISWA
// ============================================
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    
    Route::get('/dashboard/mahasiswa', [DashboardController::class, 'mahasiswa'])
        ->name('dashboard.mahasiswa');
    
    Route::get('/krs/saya', [KRSController::class, 'krsSaya'])
        ->name('krs.saya');
    Route::post('/krs/ambil', [KRSController::class, 'ambilKRS'])
        ->name('krs.ambil');
    Route::put('/krs/{krs}/drop', [KRSController::class, 'dropKRS'])
        ->name('krs.drop');
    Route::get('/krs/detail/{krs}', [KRSController::class, 'detailKRS'])
        ->name('krs.detail');
    Route::get('/krs/{krs}/export-pdf', [KRSController::class, 'exportPDF'])
        ->name('krs.export-pdf');
});

require __DIR__.'/auth.php';