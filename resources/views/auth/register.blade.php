@extends('layouts.form')

@section('title', 'Daftar Akun Baru')

@section('content')
    <div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 sm:p-12">
        <h1 class="text-3xl font-bold text-center text-brand-text mb-8">
            Buat Akun Baru Anda
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 text-sm">
                <p class="font-bold">Harap periksa kembali isian Anda.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-brand-text-muted mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        placeholder="Masukkan nama lengkap Anda"
                        class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-brand-text-muted mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        placeholder="Masukkan email valid"
                        class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-brand-text-muted mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        placeholder="Buat password baru"
                        class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-brand-text-muted mb-1">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                    @error('password_confirmation')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-brand-green hover:bg-brand-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                        Daftar
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-brand-text-muted">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-semibold text-green-600 hover:text-brand-green hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
@endsection
