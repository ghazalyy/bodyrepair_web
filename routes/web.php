<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Routes Publik (Autentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Routes Terproteksi (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'jwt'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Routes Admin Only
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // Pelanggan CRUD
        Route::resource('pelanggan', PelangganController::class)
            ->parameters(['pelanggan' => 'pelanggan']);

        // Layanan CRUD
        Route::resource('layanan', LayananController::class)
            ->except(['show'])
            ->parameters(['layanan' => 'layanan']);

        // Transaksi CRUD
        Route::resource('transaksi', TransaksiController::class)
            ->parameters(['transaksi' => 'transaksi']);

        // Cetak Nota (HTML)
        Route::get('transaksi/{transaksi}/nota', [TransaksiController::class, 'cetakNota'])
            ->name('transaksi.nota');

        // Cetak Nota (PDF)
        Route::get('transaksi/{transaksi}/nota-pdf', [TransaksiController::class, 'cetakNotaPdf'])
            ->name('transaksi.nota.pdf');

        // Input Data Keuangan Manual (hanya admin)
        Route::post('laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::delete('laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Routes Admin + Owner (Read + Laporan)
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:admin,owner'])->group(function () {

        // Laporan Keuangan
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.pdf');
        Route::get('laporan/rekap-transaksi', [LaporanController::class, 'rekapTransaksi'])->name('laporan.rekap');
    });

    /*
    |----------------------------------------------------------------------
    | API Endpoints (AJAX internal)
    |----------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('pelanggan/search', [PelangganController::class, 'search'])->name('pelanggan.search');
        Route::get('layanan/list', [LayananController::class, 'apiList'])->name('layanan.list');
    });
});
