<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaDirektoriController; // <-- Tambahkan import controller ini
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // Rute untuk Registrasi Akun Baru
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'registerStore'])->name('register.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Route API untuk Live Search Nama Siswa pada Pencatatan Pelanggaran
    Route::get('/api/siswa/search', [SiswaController::class, 'search'])->name('api.siswa.search');

    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->except('create', 'edit');
    Route::resource('jenis-pelanggaran', JenisPelanggaranController::class)->parameters(['jenis-pelanggaran' => 'jenis'])->except('show');
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

    // --- Rute Fitur Direktori Siswa 360° ---
    Route::get('/direktori-siswa', [SiswaDirektoriController::class, 'index'])->name('siswa.direktori');
    Route::get('/direktori-siswa/{id}', [SiswaDirektoriController::class, 'show'])->name('siswa.direktori.show');
});
