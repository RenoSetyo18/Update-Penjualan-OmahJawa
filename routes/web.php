<?php

use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\KokiController;
use App\Http\Controllers\BarangController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/menu', [MenuController::class, 'index']);
Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
Route::get('/kasir', [KasirController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
Route::post('/transaksi/update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');

Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
Route::post('/kasir/bayar/{id}', [KasirController::class, 'bayar'])->name('kasir.bayar');
Route::get('kasir/cetak-struk/{id}', [KasirController::class, 'cetakStruk'])->name('kasir.cetak.struk');
Route::get('/kasir/cetak/rekap', [KasirController::class, 'cetakRekap'])->name('kasir.cetak.rekap');




Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

Route::get('/koki', [KokiController::class, 'index'])->name('koki.index');

Route::get('/admin', [DashboardController::class, 'index'])->name('admin.index');


Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

