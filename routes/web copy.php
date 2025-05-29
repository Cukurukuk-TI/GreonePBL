<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminController;

// Halaman umum yang bisa diakses SEMUA ORANG (termasuk guest)
Route::get('/', fn() => view('home'));

Route::get('/produk', [ProdukController::class, 'index']);

// Halaman yang hanya bisa diakses GUEST (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login'); // tampilkan form
    Route::post('/login', [AuthController::class, 'loginPost']); // proses form login
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost']);
});

// Halaman yang hanya bisa diakses SETELAH LOGIN
Route::middleware('auth')->group(function () {
    // Halaman profil khusus user yang login
    Route::get('/profil', function () {
        if (!Auth::check()) {
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
    }
    return view('profil', ['user' => Auth::user()]);
    });

    // Halaman chart (contoh halaman khusus logged in user)
    Route::get('/chart', fn() => view('chart'));
    
    // Logout
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

// Halaman umum yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    // Route::get('/produk', [ProdukController::class, 'showToUser'])->name('produk.user');
    // Route::view('/produk', 'produk');
    Route::get('/produk', [ProdukController::class, 'showToUser'])->name('produk.user');
    //untuk menampilkan produk dalam bentuk char yang nantinya akan dilihat oleh user
    Route::get('/deskripsi-produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
    //untuk menampilakn halaman detail produk
    Route::view('/artikel', 'artikel');
    Route::view('/tentang', 'user.aboutus');
    Route::view('/kontak', 'kontak');
});


//admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('produks', ProdukController::class);
    Route::resource('kategoris', KategoriController::class);
});


