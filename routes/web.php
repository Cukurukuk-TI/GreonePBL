<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
use App\Http\Controllers\PublicArtikelController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\KategoriArtikelController;
use App\Http\Controllers\Auth\PasswordController;

//   Home (Boleh Diakses Guest)
Route::get('/', [KategoriController::class, 'indexUser'])->name('home');

Route::get('/artikel', [PublicArtikelController::class, 'index'])->name('artikel.public.index');
Route::get('/artikel/{artikel:slug}', [PublicArtikelController::class, 'show'])->name('artikel.public.show');

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
    Route::view('/tentang', 'user.aboutus');
    Route::view('/kontak', 'kontak');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.content'); // menampilkan ringkasan profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // form edit profil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');


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
    Route::get('/pesananuser', [PesananController::class, 'pesanan'])->name('user.pesanan');
});

//   Admin route
Route::middleware(['auth', 'admin', 'admin.timeout', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/produk-terlaris', [AdminController::class, 'getProdukTerlaris'])->name('produk-terlaris');

    // Profile Admin
    Route::get('/profile', [ProfileController::class, 'adminIndex'])->name('admin.profile');

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

    // Pelanggan Admin
    Route::get('pelanggan/trash', [App\Http\Controllers\Admin\PelangganController::class, 'trash'])->name('pelanggan.trash');
    Route::patch('pelanggan/{id}/restore', [App\Http\Controllers\Admin\PelangganController::class, 'restore'])->name('pelanggan.restore');
    Route::delete('pelanggan/{id}/force-delete', [App\Http\Controllers\Admin\PelangganController::class, 'forceDelete'])->name('pelanggan.forceDelete');

    Route::resource('pelanggan', PelangganController::class);

    // Artikel Admin
    Route::get('artikel/trash', [ArtikelController::class, 'trash'])->name('artikel.trash');
    Route::patch('artikel/{id}/restore', [ArtikelController::class, 'restore'])->name('artikel.restore');
    Route::delete('artikel/{id}/force-delete', [ArtikelController::class, 'forceDelete'])->name('artikel.forceDelete');
    Route::resource('artikel', ArtikelController::class);
    Route::resource('kategori-artikel', KategoriArtikelController::class)->except('show');
});

// Route untuk Verifikasi Email
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Menampilkan form untuk meminta link reset
Route::get('forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// Mengirim link reset ke email pengguna
Route::post('forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Menampilkan form untuk mereset password
Route::get('reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Memperbarui password pengguna
Route::post('reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');

// Route untuk menangani notifikasi dari Midtrans
Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');
