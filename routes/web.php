<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WilayahTerdampakController;
use App\Http\Controllers\SumberBantuanController;
use App\Http\Controllers\StokPoskoController;

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

// DAHIF-4
Route::resource('sumber-bantuan', SumberBantuanController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->names('sumber-bantuan');

// DAHIF-5
Route::prefix('stok-logistik')->name('stok-posko.')->group(function () {
    Route::get('/', [StokPoskoController::class, 'index'])->name('index');
    Route::post('/', [StokPoskoController::class, 'store'])->name('store');
    Route::patch('/{stokPosko}', [StokPoskoController::class, 'update'])->name('update');
    Route::delete('/{stokPosko}', [StokPoskoController::class, 'destroy'])->name('destroy');
});