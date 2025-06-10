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
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('dashboard');

//     Route::resource('produks', ProdukController::class);
//     Route::resource('kategoris', KategoriController::class);
// });



// Admin route - hanya bisa diakses oleh user yang sudah login dan punya role 'admin'
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        Route::resource('produks', ProdukController::class);
        Route::resource('kategoris', KategoriController::class);
        // Get active promos
        // Route::get('promos/active', [App\Http\Controllers\PromoController::class, 'getActivePromos'])
        //     ->name('promos.active');

        // // Check promo validity
        // Route::post('promos/check', [App\Http\Controllers\PromoController::class, 'checkPromo'])
        //     ->name('promos.check');
        Route::resource('promos', App\Http\Controllers\PromoController::class);
        Route::patch('promos/{promo}/toggle-status', [App\Http\Controllers\PromoController::class, 'toggleStatus'])
            ->name('promos.toggle-status');


    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('alamat', AlamatController::class);
});



// // Promo Routes - Admin
// Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
//     // Promo Management
//     Route::resource('promos', App\Http\Controllers\Admin\PromoController::class);
//     Route::patch('promos/{promo}/toggle-status', [App\Http\Controllers\Admin\PromoController::class, 'toggleStatus'])
//         ->name('promos.toggle-status');
// });

// Jika ingin menambahkan API routes untuk promo
Route::prefix('api')->name('api.')->group(function () {
    ;
});

// Routes untuk pesanan (user)
Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan/create/{produk}', [PesananController::class, 'create'])->name('pesanans.create');
    Route::post('/pesanan/store', [PesananController::class, 'store'])->name('pesanans.store');
    Route::get('/pesanan/success/{id}', [PesananController::class, 'success'])->name('pesanans.success');
});

// Routes untuk admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pesanans', [PesananController::class, 'index'])->name('pesanans.index');
    Route::patch('/pesanans/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanans.update-status');
});