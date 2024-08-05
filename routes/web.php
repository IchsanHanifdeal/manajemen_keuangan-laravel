<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'auth'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard/kategori', [KategoriController::class, 'index'])->name('kategori');
Route::post('/dashboard/kategori/tambah', [KategoriController::class, 'store'])->name('store.kategori');
Route::put('/dashboard/kategori/{id_kategori}/update', [KategoriController::class, 'update'])->name('update.kategori');
Route::delete('/dashboard/kategori/hapus/{id_kategori}', [KategoriController::class, 'destroy'])->name('delete.kategori');

Route::get('/dashboard/bank', [BankController::class, 'index'])->name('bank');
Route::post('/dashboard/bank/tambah', [BankController::class, 'store'])->name('store.bank');
Route::put('/dashboard/bank/{id_bank}/update', [BankController::class, 'update'])->name('update.bank');
Route::delete('/dashboard/bank/hapus/{id_bank}', [BankController::class, 'destroy'])->name('delete.bank');

Route::get('/dashboard/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
Route::post('/dashboard/transaksi/tambah', [TransaksiController::class, 'store'])->name('store.transaksi');
Route::put('/dashboard/transaksi/{id_transaksi}/update', [TransaksiController::class, 'update'])->name('update.transaksi');
Route::delete('/dashboard/transaksi/hapus/{id_transaksi}', [TransaksiController::class, 'destroy'])->name('delete.transaksi');

Route::get('/dashboard/hutang', [HutangController::class, 'index'])->name('hutang');
Route::post('/dashboard/hutang/tambah', [hutangController::class, 'store'])->name('store.hutang');
Route::put('/dashboard/hutang/{id_hutang}/update', [hutangController::class, 'update'])->name('update.hutang');
Route::delete('/dashboard/hutang/hapus/{id_hutang}', [hutangController::class, 'destroy'])->name('delete.hutang');

Route::get('/dashboard/piutang', [PiutangController::class, 'index'])->name('piutang');
Route::post('/dashboard/piutang/tambah', [piutangController::class, 'store'])->name('store.piutang');
Route::put('/dashboard/piutang/{id_piutang}/update', [piutangController::class, 'update'])->name('update.piutang');
Route::delete('/dashboard/piutang/hapus/{id_piutang}', [piutangController::class, 'destroy'])->name('delete.piutang');

Route::get('/dashboard/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
Route::post('/dashboard/pengguna/tambah', [penggunaController::class, 'store'])->name('store.pengguna');
Route::put('/dashboard/pengguna/{id_user}/update', [penggunaController::class, 'update'])->name('update.pengguna');
Route::delete('/dashboard/pengguna/hapus/{id_user}', [penggunaController::class, 'destroy'])->name('delete.pengguna');

Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/dashboard/profile/{id_user}', [ProfileController::class, 'update'])->name('update.profile');
Route::put('/dashboard/profile/ubah_password/{id_user}', [ProfileController::class, 'updatePassword'])->name('update.password');

Route::get('/dashboard/laporan', [TransaksiController::class, 'index'])->name('laporan');
// });
