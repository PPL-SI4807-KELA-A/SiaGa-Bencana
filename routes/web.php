<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WilayahTerdampakController;
use App\Http\Controllers\SumberBantuanController;

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