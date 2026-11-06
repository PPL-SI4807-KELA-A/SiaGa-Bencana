<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahTerdampakController;

Route::get('/', function () {
    return view('dashboard');
});

// DAHIF-1
Route::resource('admin/wilayah-terdampak', WilayahTerdampakController::class)
    ->only(['index', 'create', 'store'])
    ->names('wilayah-terdampak');
