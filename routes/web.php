<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\ProdukController;

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
    // Route::view('/produk', 'produk');
    Route::view('/artikel', 'artikel');
    Route::view('/tentang', 'user.aboutus');
    Route::view('/kontak', 'kontak');
});