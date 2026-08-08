<?php

use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\PeriodePendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'landing'])->name('landing');
Route::get('/kelas', [PublicPageController::class, 'katalog'])->name('katalog');
Route::get('/tentang', [PublicPageController::class, 'about'])->name('about');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Khusus Admin (F7, F8, F9, F10, F14, F15)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('mata-pelajaran', MataPelajaranController::class)->except('show');
    Route::resource('periode', PeriodePendaftaranController::class)->except('show');
    Route::resource('guru', GuruController::class)->except('show');
    Route::resource('kelas', KelasController::class)->except('show');
    // Route kelas, laporan, dll ditambah di sini nanti
});

// Khusus Guru (F12, F13)
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // Route presensi & input nilai ditambah di sini nanti
});

// Khusus Siswa (F4, F5, F6, F14)
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    // Route pilih kelas, pendaftaran, pembayaran, pembatalan ditambah di sini nanti
});

require __DIR__ . '/auth.php';
