<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PemohonAuthController;
use App\Http\Controllers\PemohonKunjunganController;
use App\Http\Controllers\StaffAuthController;
use Illuminate\Support\Facades\Route;

// Landing Page Routes (PT ABCD)
Route::get('/', [LandingController::class, 'home'])->name('landing.home');
Route::get('/jadwal-kunjungan', [LandingController::class, 'jadwal'])->name('landing.jadwal');
Route::get('/struktur-karyawan', [LandingController::class, 'struktur'])->name('landing.struktur');

// Pemohon Auth Routes (Daftar & Login)
Route::prefix('pemohon')->name('pemohon.')->group(function (): void {
    Route::get('/daftar', [PemohonAuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [PemohonAuthController::class, 'register'])->name('register.store');
    Route::get('/login', [PemohonAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PemohonAuthController::class, 'login'])->name('login.authenticate');
    Route::post('/logout', [PemohonAuthController::class, 'logout'])->name('logout');

    // Pemohon Protected Routes (Kunjungan CRUD & Histori Kunjungan Read)
    Route::middleware('auth.pemohon')->group(function (): void {
        Route::get('/kunjungan', [PemohonKunjunganController::class, 'index'])->name('kunjungan.index');
        Route::post('/kunjungan', [PemohonKunjunganController::class, 'store'])->name('kunjungan.store');
        Route::put('/kunjungan/{id_kunjungan}', [PemohonKunjunganController::class, 'update'])->name('kunjungan.update');
        Route::delete('/kunjungan/{id_kunjungan}', [PemohonKunjunganController::class, 'destroy'])->name('kunjungan.destroy');
        Route::get('/histori', [PemohonKunjunganController::class, 'histori'])->name('histori');
    });
});

// Admin Portal Routes
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [StaffAuthController::class, 'showAdminLogin'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'authenticateAdmin'])->name('login.authenticate');
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function (): void {
        Route::get('/', fn () => redirect()->route('admin.kunjungan.index'))->name('dashboard');

        // Pengajuan Kunjungan (Read, Update: status Y/T & catatan ditolak, Delete)
        Route::get('/kunjungan', [AdminController::class, 'kunjunganIndex'])->name('kunjungan.index');
        Route::put('/kunjungan/{id_kunjungan}', [AdminController::class, 'kunjunganUpdate'])->name('kunjungan.update');
        Route::delete('/kunjungan/{id_kunjungan}', [AdminController::class, 'kunjunganDestroy'])->name('kunjungan.destroy');

        // Setting Kunjungan (Create, Read, Update, Delete)
        Route::get('/settings-kunjungan', [AdminController::class, 'settingsKunjunganIndex'])->name('settings-kunjungan.index');
        Route::post('/settings-kunjungan', [AdminController::class, 'settingsKunjunganStore'])->name('settings-kunjungan.store');
        Route::put('/settings-kunjungan/{id_settings_kunjungan}', [AdminController::class, 'settingsKunjunganUpdate'])->name('settings-kunjungan.update');
        Route::delete('/settings-kunjungan/{id_settings_kunjungan}', [AdminController::class, 'settingsKunjunganDestroy'])->name('settings-kunjungan.destroy');

        // Kegiatan (Create, Read, Update, Delete)
        Route::get('/kegiatan', [AdminController::class, 'kegiatanIndex'])->name('kegiatan.index');
        Route::post('/kegiatan', [AdminController::class, 'kegiatanStore'])->name('kegiatan.store');
        Route::put('/kegiatan/{id_settings_kegiatan}', [AdminController::class, 'kegiatanUpdate'])->name('kegiatan.update');
        Route::delete('/kegiatan/{id_settings_kegiatan}', [AdminController::class, 'kegiatanDestroy'])->name('kegiatan.destroy');

        // Struktur Karyawan / Penduduk & Jabatan CRUD
        Route::get('/karyawan', [AdminController::class, 'karyawanIndex'])->name('karyawan.index');
        Route::post('/karyawan', [AdminController::class, 'karyawanStore'])->name('karyawan.store');
        Route::put('/karyawan/{id_penduduk}', [AdminController::class, 'karyawanUpdate'])->name('karyawan.update');
        Route::delete('/karyawan/{id_penduduk}', [AdminController::class, 'karyawanDestroy'])->name('karyawan.destroy');

        Route::post('/jabatan', [AdminController::class, 'jabatanStore'])->name('jabatan.store');
        Route::put('/jabatan/{id_jabatan}', [AdminController::class, 'jabatanUpdate'])->name('jabatan.update');
        Route::delete('/jabatan/{id_jabatan}', [AdminController::class, 'jabatanDestroy'])->name('jabatan.destroy');
    });
});

// Karyawan (Penduduk) Portal Routes
Route::prefix('karyawan')->name('karyawan.')->group(function (): void {
    Route::get('/login', [StaffAuthController::class, 'showKaryawanLogin'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'authenticateKaryawan'])->name('login.authenticate');
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.karyawan')->group(function (): void {
        Route::get('/', fn () => redirect()->route('karyawan.kunjungan.index'))->name('dashboard');

        // Kunjungan (Read, Update: status Y/T & catatan ditolak)
        Route::get('/kunjungan', [KaryawanController::class, 'kunjunganIndex'])->name('kunjungan.index');
        Route::put('/kunjungan/{id_kunjungan}', [KaryawanController::class, 'kunjunganUpdate'])->name('kunjungan.update');

        // Setting Kunjungan (Create, Read, Update, Delete)
        Route::get('/settings-kunjungan', [KaryawanController::class, 'settingsKunjunganIndex'])->name('settings-kunjungan.index');
        Route::post('/settings-kunjungan', [KaryawanController::class, 'settingsKunjunganStore'])->name('settings-kunjungan.store');
        Route::put('/settings-kunjungan/{id_settings_kunjungan}', [KaryawanController::class, 'settingsKunjunganUpdate'])->name('settings-kunjungan.update');
        Route::delete('/settings-kunjungan/{id_settings_kunjungan}', [KaryawanController::class, 'settingsKunjunganDestroy'])->name('settings-kunjungan.destroy');

        // Kegiatan (Create, Read, Update, Delete)
        Route::get('/kegiatan', [KaryawanController::class, 'kegiatanIndex'])->name('kegiatan.index');
        Route::post('/kegiatan', [KaryawanController::class, 'kegiatanStore'])->name('kegiatan.store');
        Route::put('/kegiatan/{id_settings_kegiatan}', [KaryawanController::class, 'kegiatanUpdate'])->name('kegiatan.update');
        Route::delete('/kegiatan/{id_settings_kegiatan}', [KaryawanController::class, 'kegiatanDestroy'])->name('kegiatan.destroy');
    });
});
