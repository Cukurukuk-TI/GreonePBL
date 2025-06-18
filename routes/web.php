<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/user/home', function () {
    return view('user.home');
})->name('home.index');

Route::get('/user/artikel', function () {
    return view('user.artikel');
})->name('artikel.index');

Route::get('/user/kontak', function () {
    return view('user.kontak');
})->name('kontak.index');

Route::get('/user/tentang', function () {
    return view('user.tentang');
})->name('tentang.index');

Route::get('/user/produk', [ProdukController::class, 'index'])->name('produk.index');

Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index']);

Route::prefix('admin')->name('admin.')->group(function () {

    // Route default untuk /admin, akan redirect ke dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Definisi route untuk setiap halaman di panel admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/admin-profile', [AdminProfileController::class, 'profile'])->name('profile');
    Route::resource('/kategori', KategoriController::class);
    Route::get('/produk', [ProdukController::class, 'adminIndex'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
    Route::get('/pesanan', [App\Http\Controllers\PesananController::class, 'index'])->name('pesanan');
    Route::get('/promo', [App\Http\Controllers\PromoController::class, 'index'])->name('promo');
    Route::get('/alamat', [App\Http\Controllers\AlamatController::class, 'index'])->name('alamat');
    Route::get('/artikel', [App\Http\Controllers\ArtikelController::class, 'index'])->name('artikel');
    Route::get('/testimoni', [App\Http\Controllers\TestimoniController::class, 'index'])->name('testimoni');
    Route::get('/akun-pelanggan', [App\Http\Controllers\AkunPelangganController::class, 'index'])->name('akun-pelanggan');
    Route::get('/role-pengguna', [App\Http\Controllers\RolePenggunaController::class, 'index'])->name('role-pengguna');
    // ... dan route lainnya sesuai menu
});