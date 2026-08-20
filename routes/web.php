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
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\MasterMenuController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/kasir', [KasirController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/transaksi/update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');
    Route::get('/transaksi/get-by-table', [TransaksiController::class, 'getTransactionByTable'])->name('transaksi.getByTable');
    Route::delete('/transaksi/{transaksiId}/detail/{detailId}', [TransaksiController::class, 'deleteDetail'])->name('transaksi.deleteDetail');
    Route::put('/transaksi/{transaksiId}/detail/{detailId}', [TransaksiController::class, 'updateDetail'])->name('transaksi.updateDetail');

    // Menu Status Routes
    Route::put('/transaksi/{transaksiId}/menu-status/{detailId}', [TransaksiController::class, 'updateMenuStatus'])->name('transaksi.updateMenuStatus');
    Route::get('/transaksi/{transaksiId}/menu-status', [TransaksiController::class, 'getMenuStatus'])->name('transaksi.getMenuStatus');
    Route::get('/transaksi/{transaksiId}/detail-modal', [TransaksiController::class, 'getTransaksiDetailForModal'])->name('transaksi.detailModal');
    Route::get('/transaksi/ready-menus-notification', [TransaksiController::class, 'getReadyMenusNotification'])->name('transaksi.readyMenusNotification');
    Route::get('/transaksi/all-for-refresh', [TransaksiController::class, 'getAllTransaksisForRefresh'])->name('transaksi.allForRefresh');

    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/bayar/{id}', [KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::get('kasir/cetak-struk/{id}', [KasirController::class, 'cetakStruk'])->name('kasir.cetak.struk');
    Route::get('/kasir/cetak/rekap', [KasirController::class, 'cetakRekap'])->name('kasir.cetak.rekap');
    Route::get('/transaksi/cetak/rekap', [TransaksiController::class, 'cetakRekap'])->name('transaksi.cetak.rekap');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('/laporan/tren-penjualan', [LaporanController::class, 'trenPenjualan'])->name('laporan.tren-penjualan');
    Route::get('/laporan/perbandingan-periode', [LaporanController::class, 'perbandinganPeriode'])->name('laporan.perbandingan-periode');

    // Laporan General Routes
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/cetak-penjualan', [LaporanController::class, 'cetakPenjualan'])->name('laporan.cetak-penjualan');
    Route::get('/laporan/menu-terlaris', [LaporanController::class, 'menuTerlaris'])->name('laporan.menu-terlaris');
    Route::get('/laporan/cetak-menu-terlaris', [LaporanController::class, 'cetakMenuTerlaris'])->name('laporan.cetak-menu-terlaris');
    Route::get('/laporan/metode-pembayaran', [LaporanController::class, 'metodePembayaran'])->name('laporan.metode-pembayaran');
    Route::get('/laporan/cetak-metode-pembayaran', [LaporanController::class, 'cetakMetodePembayaran'])->name('laporan.cetak-metode-pembayaran');

    Route::get('/koki', [KokiController::class, 'index'])->name('koki.index');

    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.index');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Master Data Routes
    Route::prefix('master')->name('master-')->group(function () {
        // Master Karyawan
        Route::resource('karyawan', KaryawanController::class);

        // Master Jenis Menu
        Route::resource('jenis', JenisController::class);

        // Master Menu
        Route::resource('menu', MasterMenuController::class, ['as' => 'menu']);
    });

    // Backward compatibility routes
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    Route::get('/jenis', [JenisController::class, 'index'])->name('jenis.index');
    Route::get('/jenis/create', [JenisController::class, 'create'])->name('jenis.create');
    Route::post('/jenis', [JenisController::class, 'store'])->name('jenis.store');
    Route::get('/jenis/{jenis}/edit', [JenisController::class, 'edit'])->name('jenis.edit');
    Route::put('/jenis/{jenis}', [JenisController::class, 'update'])->name('jenis.update');
    Route::delete('/jenis/{jenis}', [JenisController::class, 'destroy'])->name('jenis.destroy');

    Route::get('/master-menu', [MasterMenuController::class, 'index'])->name('master-menu.index');
    Route::get('/master-menu/create', [MasterMenuController::class, 'create'])->name('master-menu.create');
    Route::post('/master-menu', [MasterMenuController::class, 'store'])->name('master-menu.store');
    Route::get('/master-menu/{menu}/edit', [MasterMenuController::class, 'edit'])->name('master-menu.edit');
    Route::put('/master-menu/{menu}', [MasterMenuController::class, 'update'])->name('master-menu.update');
    Route::delete('/master-menu/{menu}', [MasterMenuController::class, 'destroy'])->name('master-menu.destroy');
});

