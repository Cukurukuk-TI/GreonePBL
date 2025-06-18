<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Tampilkan form login
    public function login()
    {
        return view('auth.login');
    }

    // Proses login

    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect('/');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }


    // public function loginPost(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect('/profil');
    //     }

    //     return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    // }

    // Tampilkan form register
    public function register()
    {
        return view('auth.register');
    }

    // Proses register
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(), 
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Memicu event untuk mengirim email verifikasi
        event(new Registered($user));

        // Langsung login-kan user
        Auth::login($user);

        // Arahkan ke halaman pemberitahuan verifikasi
        return redirect()->route('verification.notice');
    }

    // Halaman profil setelah login
    public function profile()
    {
        return view('profil', ['user' => Auth::user()]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
