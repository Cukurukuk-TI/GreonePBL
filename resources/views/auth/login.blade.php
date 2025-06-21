{{-- Menggunakan layout yang baru kita buat --}}
@extends('layouts.form')

{{-- Mengatur judul halaman --}}
@section('title', 'Login')

{{-- Mengisi section 'content' di layout --}}
@section('content')
    <div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 sm:p-12">
        <h1 class="text-3xl font-bold text-center text-brand-text mb-8">
            Login ke Akun Anda
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 text-sm">
                <p class="font-bold">Terjadi Kesalahan</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-brand-text-muted mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email anda"
                           class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-brand-text-muted mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password anda"
                           class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                        <span class="ms-2 text-sm text-brand-text-muted">Ingat saya</span>
                    </label>
                    <a href=
                    {{-- "{{ route('password.request') }}"  --}}
                    class="text-sm font-medium text-green-600 hover:text-brand-green hover:underline">
                        Lupa Password?
                    </a>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-brand-green hover:bg-brand-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                        Login
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-brand-text-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-brand-green hover:underline">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>

    <script>
        // untuk hidden dan seek

    </script>
@endsection
