{{-- Menggunakan layout form yang konsisten --}}
@extends('layouts.form')

@section('title', 'Lupa Password')

@section('content')
<div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 sm:p-12">
    <h1 class="text-3xl font-bold text-center text-brand-text mb-4">
        Lupa Password Anda?
    </h1>
    <p class="text-center text-brand-text-muted mb-8">
        Masukkan email Anda, kami akan mengirimkan tautan untuk mengatur ulang password.
    </p>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-700 bg-green-100 p-3 rounded-md text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-brand-text-muted mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="Masukkan email terdaftar"
                    class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 transition-colors duration-200 focus:outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-brand-green hover:bg-brand-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                    Kirim Tautan Reset
                </button>
            </div>
        </div>
    </form>

    <div class="mt-8 text-center">
        <p class="text-sm text-brand-text-muted">
            Tiba-tiba ingat password?
            <a href="{{ route('login') }}"
                class="font-semibold text-green-600 hover:text-brand-green hover:underline">
                Kembali ke Login
            </a>
        </p>
    </div>
</div>
@endsection