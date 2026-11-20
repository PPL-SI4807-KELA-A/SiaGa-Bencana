<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PermintaanMendesakController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\WilayahTerdampakController;
use App\Http\Controllers\SumberBantuanController;
use App\Http\Controllers\KategoriBencanaController;
use App\Http\Controllers\LaporanKejadianController;
use App\Http\Controllers\LaporanKejadianPublikController;
use App\Http\Controllers\PengungsiController;
use App\Models\PermintaanMendesak;

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
//DAFIF-9
Route::prefix('lapor-kejadian')->name('laporan-kejadian.publik.')->group(function () {
    Route::get('/', [LaporanKejadianPublikController::class, 'create'])->name('create');
    Route::post('/', [LaporanKejadianPublikController::class, 'store'])->name('store');
    Route::get('/cek', [LaporanKejadianPublikController::class, 'cek'])->name('cek');
    Route::get('/{kode}', [LaporanKejadianPublikController::class, 'show'])->name('show');
    Route::get('/{kode}/ubah', [LaporanKejadianPublikController::class, 'edit'])->name('edit');
    Route::put('/{kode}', [LaporanKejadianPublikController::class, 'update'])->name('update');
    Route::delete('/{kode}', [LaporanKejadianPublikController::class, 'destroy'])->name('destroy');
});
Route::get('laporan-kejadian', [LaporanKejadianController::class, 'index'])->name('laporan-kejadian.index');
Route::get('laporan-kejadian/{laporan}', [LaporanKejadianController::class, 'show'])->name('laporan-kejadian.show');

// DAHIF-3
Route::resource('relawan/pengungsi', PengungsiController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->parameters(['pengungsi' => 'pengungsi'])
    ->names('pengungsi');