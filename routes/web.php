<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\WilayahTerdampakController;
use App\Http\Controllers\SumberBantuanController;
use App\Http\Controllers\KategoriBencanaController;

Route::get('/', function () {
    return view('dashboard');
});

// CHORE Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.proses');
});
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// DAHIF-1
Route::middleware(['auth', 'peran:Admin BPBD'])->group(function () {
    Route::resource('admin/wilayah-terdampak', WilayahTerdampakController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('wilayah-terdampak');
});

// DAHIF-6
Route::resource('admin/barang', BarangController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->names('barang');

// DAHIF-2
Route::middleware(['auth', 'peran:Admin BPBD'])->group(function () {
    Route::patch('admin/posko/{posko}/status-operasional', [PoskoController::class, 'toggleStatusOperasional'])
        ->name('posko.status-operasional');
    Route::patch('admin/posko/{posko}/aktif', [PoskoController::class, 'toggleAktif'])
        ->name('posko.aktif');
    Route::resource('admin/posko', PoskoController::class)
        ->except(['destroy'])
        ->names('posko');
});
Route::get('posko', [\App\Http\Controllers\PoskoPublikController::class, 'index'])
    ->name('posko.publik');

// DAHIF-4
Route::resource('sumber-bantuan', SumberBantuanController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->names('sumber-bantuan');
// DAFIF-8
Route::resource('kategori-bencana', KategoriBencanaController::class)
    ->except('show')
    ->parameters(['kategori-bencana' => 'kategori_bencana']);
