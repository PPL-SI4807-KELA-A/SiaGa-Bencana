<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\WilayahTerdampakController;

Route::get('/', function () {
    return view('dashboard');
});

// DAHIF-1
Route::resource('admin/wilayah-terdampak', WilayahTerdampakController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    ->names('wilayah-terdampak');

// DAHIF-6
Route::resource('admin/barang', BarangController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->names('barang');

// DAHIF-2
Route::patch('admin/posko/{posko}/status-operasional', [PoskoController::class, 'toggleStatusOperasional'])
    ->name('posko.status-operasional');
Route::patch('admin/posko/{posko}/aktif', [PoskoController::class, 'toggleAktif'])
    ->name('posko.aktif');
Route::get('posko', [\App\Http\Controllers\PoskoPublikController::class, 'index'])
    ->name('posko.publik');

Route::resource('admin/posko', PoskoController::class)
    ->except(['destroy'])
    ->names('posko');
