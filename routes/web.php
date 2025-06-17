<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeranjangController;

//   Home (Boleh Diakses Guest)
Route::get('/', [KategoriController::class, 'indexUser'])->name('home');

//   Guest-only routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost']);
});

//   Semua route ini HANYA untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // Produk
    Route::get('/produk', [ProdukController::class, 'showToUser'])->name('produk.user');
    Route::get('/deskripsi-produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

    // Halaman statis
    Route::view('/artikel', 'artikel');
    Route::view('/tentang', 'user.aboutus');
    Route::view('/kontak', 'kontak');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.content'); // menampilkan ringkasan profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // form edit profil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Alamat
    Route::resource('alamat', AlamatController::class);

    // Halaman chart (opsional)
    Route::get('/chart', fn() => view('chart'));

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::put('/keranjang/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::delete('/keranjang', [KeranjangController::class, 'clear'])->name('keranjang.clear');
    Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
    Route::post('/checkout', [KeranjangController::class, 'processCheckout'])->name('keranjang.process');

    // Pesanan user
    Route::get('/pesanan/create/{produk}', [PesananController::class, 'create'])->name('pesanans.create');
    Route::post('/pesanan/store', [PesananController::class, 'store'])->name('pesanans.store');
    Route::get('/pesanan/success/{id}', [PesananController::class, 'success'])->name('pesanans.success');
});

//   Admin route tetap sama
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/produk-terlaris', [AdminController::class, 'getProdukTerlaris'])->name('produk-terlaris');

    Route::resource('produks', ProdukController::class);
    Route::resource('kategoris', KategoriController::class);
    Route::resource('promos', PromoController::class);
    Route::patch('promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus'])->name('promos.toggle-status');

    // Pesanan Admin
    Route::get('/pesanans', [PesananController::class, 'index'])->name('pesanans.index');
    Route::patch('/pesanans/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('pesanans.update-status');
    Route::get('/pesanans/cancelled', [PesananController::class, 'cancelled'])->name('pesanans.cancelled');
    Route::patch('/pesanans/{pesanan}/restore', [PesananController::class, 'restore'])->name('pesanans.restore');
    Route::delete('/pesanans/{pesanan}/force-delete', [PesananController::class, 'forceDelete'])->name('pesanans.force-delete');
    Route::get('/pesanans/{pesanan}', [PesananController::class, 'show'])->name('pesanans.show');
});
