@extends('layouts.appnoslider')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100" style="padding-top: 60px;">
    <div class="w-full max-w-lg p-10 bg-white rounded-xl shadow-xl text-center">

        <div class="mb-6">
            {{-- Ikon email atau centang untuk visual --}}
            <svg class="mx-auto h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <h2 class="text-3xl font-bold mb-4 text-gray-700">Verifikasi Email Anda</h2>

        {{-- Pesan konfirmasi jika email berhasil dikirim ulang --}}
        @if (session('message'))
            <div class="mb-4 text-sm font-medium text-green-700 bg-green-100 p-3 rounded-md">
                Tautan verifikasi baru telah berhasil dikirimkan ke alamat email Anda.
            </div>
        @endif

        <p class="text-gray-600 mb-6">
            Kami telah mengirimkan email verifikasi ke <strong class="text-gray-800">{{ Auth::user()->email }}</strong>.
            <br>
            Silakan cek kotak masuk Anda dan klik tautan di dalamnya untuk mengaktifkan akun Anda.
        </p>

        <p class="text-sm text-gray-500 mb-4">
            Masih belum ada? Kirim ulang email verifikasi.
        </p>

        {{-- Form untuk kirim ulang email --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition duration-200">
                Kirim Ulang
            </button>
        </form>

        {{-- Tombol Logout --}}
        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:underline">
                    Bukan Anda? Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
