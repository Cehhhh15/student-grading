<?php

/**
 * ============================================================
 * FILE: web.php
 * LOKASI: routes/web.php
 * ============================================================
 * Definisi semua route (URL) dalam aplikasi SIMPEL.
 *
 * STRUKTUR ROUTE:
 *   / (publik)          : Redirect ke login
 *   /login              : Halaman login
 *   /logout             : Proses logout
 *
 *   /admin/*            : Khusus Admin
 *   /guru/*             : Khusus Guru
 *   /siswa/*            : Khusus Siswa
 *
 * MIDDLEWARE YANG DIGUNAKAN:
 *   - 'auth'            : Harus login (bawaan Laravel)
 *   - 'role:admin'      : Harus memiliki role admin
 *   - 'role:guru'       : Harus memiliki role guru
 *   - 'role:siswa'      : Harus memiliki role siswa
 * ============================================================
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROUTE PUBLIK: Redirect ke login
// ============================================================

Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================
// ROUTE AUTENTIKASI (Tidak perlu login)
// ============================================================

Route::middleware('guest')->group(function () {
    // Tampilkan halaman login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // Proses login
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route logout (perlu login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================================
// ROUTE DASHBOARD GENERIK (untuk redirect setelah login)
// ============================================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru'  => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// ============================================================
// ROUTE ADMIN (Hanya untuk role: admin)
// ============================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ---- Manajemen Data Siswa ----
    Route::get('/siswas', [AdminController::class, 'siswas'])->name('siswas');
    Route::get('/siswas/create', [AdminController::class, 'siswasCreate'])->name('siswas.create');
    Route::post('/siswas', [AdminController::class, 'siswasStore'])->name('siswas.store');
    Route::get('/siswas/{siswa}/edit', [AdminController::class, 'siswasEdit'])->name('siswas.edit');
    Route::put('/siswas/{siswa}', [AdminController::class, 'siswasUpdate'])->name('siswas.update');
    Route::delete('/siswas/{siswa}', [AdminController::class, 'siswasDestroy'])->name('siswas.destroy');

    // ---- Manajemen Data Guru ----
    Route::get('/guru', [AdminController::class, 'gurus'])->name('guru');
    Route::get('/guru/create', [AdminController::class, 'gurusCreate'])->name('guru.create');
    Route::post('/guru', [AdminController::class, 'gurusStore'])->name('guru.store');
    Route::get('/guru/{guru}/edit', [AdminController::class, 'gurusEdit'])->name('guru.edit');
    Route::put('/guru/{guru}', [AdminController::class, 'gurusUpdate'])->name('guru.update');
    Route::delete('/guru/{guru}', [AdminController::class, 'gurusDestroy'])->name('guru.destroy');

    // ---- Manajemen Mata Pelajaran ----
    Route::get('/mapels', [AdminController::class, 'mapels'])->name('mapels');
    Route::post('/mapels', [AdminController::class, 'mapelsStore'])->name('mapels.store');
    Route::put('/mapels/{mapel}', [AdminController::class, 'mapelsUpdate'])->name('mapels.update');
    Route::delete('/mapels/{mapel}', [AdminController::class, 'mapelsDestroy'])->name('mapels.destroy');

    // ---- Laporan Nilai ----
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/pdf', [AdminController::class, 'laporanPdf'])->name('laporan.pdf');
});

// ============================================================
// ROUTE GURU (Hanya untuk role: guru)
// ============================================================

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {

    // Dashboard Guru
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');

    // Input Nilai: tampil form dan proses simpan
    Route::get('/input-nilai', [GuruController::class, 'inputNilai'])->name('input-nilai');
    Route::post('/input-nilai', [GuruController::class, 'simpanNilai'])->name('simpan-nilai');

    // Rekap Nilai
    Route::get('/rekap-nilai', [GuruController::class, 'rekapNilai'])->name('rekap-nilai');
});

// ============================================================
// ROUTE SISWA (Hanya untuk role: siswa)
// ============================================================

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    // Dashboard Siswa
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');

    // Lihat nilai pribadi
    Route::get('/nilai-saya', [SiswaController::class, 'nilaiSaya'])->name('nilai-saya');
});
