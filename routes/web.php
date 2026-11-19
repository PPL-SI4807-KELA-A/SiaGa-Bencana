<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PermintaanMendesakController;
use App\Http\Controllers\WilayahTerdampakController;
use App\Models\PermintaanMendesak;

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

// DAHIF-7: semua route detail menggunakan kode pelacakan sebagai route key.
Route::bind('kode_pelacakan', function (string $value) {
    return PermintaanMendesak::query()
        ->where('kode_pelacakan', $value)
        ->firstOrFail();
});

Route::get('/permintaan-mendesak/create', [PermintaanMendesakController::class, 'create'])
    ->name('permintaan-mendesak.create');
Route::post('/permintaan-mendesak', [PermintaanMendesakController::class, 'store'])
    ->name('permintaan-mendesak.store');
Route::get('/permintaan-mendesak/lacak', [PermintaanMendesakController::class, 'lacak'])
    ->name('permintaan-mendesak.lacak');
Route::post('/permintaan-mendesak/lacak', [PermintaanMendesakController::class, 'prosesLacak'])
    ->name('permintaan-mendesak.proses-lacak');
Route::get('/permintaan-mendesak/{kode_pelacakan}/edit', [PermintaanMendesakController::class, 'edit'])
    ->name('permintaan-mendesak.edit');
Route::put('/permintaan-mendesak/{kode_pelacakan}', [PermintaanMendesakController::class, 'update'])
    ->name('permintaan-mendesak.update');
Route::patch('/permintaan-mendesak/{kode_pelacakan}', [PermintaanMendesakController::class, 'update']);
Route::delete('/permintaan-mendesak/{kode_pelacakan}', [PermintaanMendesakController::class, 'destroy'])
    ->name('permintaan-mendesak.destroy');
Route::get('/permintaan-mendesak/{kode_pelacakan}', [PermintaanMendesakController::class, 'show'])
    ->name('permintaan-mendesak.show');

Route::get('/admin/permintaan-mendesak', [PermintaanMendesakController::class, 'index'])
    ->name('admin.permintaan-mendesak.index');
