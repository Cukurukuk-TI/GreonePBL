{{-- Menggunakan layout form yang konsisten --}}
@extends('layouts.form')

@section('title', 'Atur Password Baru')

@section('content')
<div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 sm:p-12">
    <h1 class="text-3xl font-bold text-center text-brand-text mb-8">
        Atur Password Baru
    </h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-brand-text-muted mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ request('email') }}" required readonly
                    class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 bg-gray-100 text-gray-500 cursor-not-allowed">
                @error('email')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-brand-text-muted mb-1">Password Baru</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    placeholder="Masukkan password baru"
                    class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-brand-text-muted mb-1">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="Ulangi password baru"
                    class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-brand-green hover:bg-brand-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                    Reset Password
                </button>
            </div>
        </div>
    </form>
</div>
@endsection