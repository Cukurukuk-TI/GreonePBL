{{-- Menggunakan layout form yang konsisten dengan login/register --}}
@extends('layouts.form')

@section('title', 'Verifikasi Email')

@section('content')
<div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 sm:p-12 text-center">

    {{-- Ikon email untuk visual --}}
    <div class="mb-6">
        <svg class="mx-auto h-16 w-16 text-brand-green" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-brand-text mb-4">
        Verifikasi Email Anda
    </h1>

    {{-- Pesan konfirmasi jika email berhasil dikirim ulang --}}
    @if (session('message'))
        <div class="mb-4 text-sm font-medium text-green-700 bg-green-100 p-3 rounded-md">
            Tautan verifikasi baru telah berhasil dikirimkan ke alamat email Anda.
        </div>
    @endif

    <p class="text-brand-text-muted mb-6">
        Kami telah mengirimkan tautan verifikasi ke <strong class="text-brand-text">{{ Auth::user()->email }}</strong>.
        Silakan periksa kotak masuk dan klik tautan di dalamnya untuk mengaktifkan akun Anda.
    </p>

    <p class="text-sm text-gray-500 mb-4">
        Tidak menerima email?
    </p>

    {{-- Form untuk kirim ulang email --}}
    <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
        @csrf
        <button type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-brand-green hover:bg-brand-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
            Kirim Ulang Email
        </button>
    </form>

    {{-- Tombol Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-sm text-brand-text-muted hover:text-brand-text hover:underline">
            Bukan Anda? Logout
        </button>
    </form>
</div>
@endsection