@extends('layouts.appnoslider')

@section('title', 'Konfirmasi Pesanan')

@push('styles')
<style>
    @keyframes checkmark {
        0% { stroke-dashoffset: 50; }
        100% { stroke-dashoffset: 0; }
    }
    .checkmark__circle {
        stroke-dasharray: 50;
        stroke-dashoffset: 50;
        animation: checkmark 0.5s ease-in-out forwards;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 md:py-16">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8 md:p-12 text-center">

            {{-- Ikon Sukses --}}
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="checkmark__circle" cx="12" cy="12" r="10" stroke-width="2" />
                    <path class="checkmark__check" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-800">Pesanan Dibuat!</h1>
            <p class="text-gray-600 mt-3 text-base leading-relaxed">
                Terima kasih, pesanan Anda telah kami catat. Silakan periksa kembali detail pesanan Anda sebelum melanjutkan ke tahap pembayaran.
            </p>

            {{-- Detail Pesanan --}}
            <div class="mt-8 text-left bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Nomor Pesanan</span>
                    <span class="font-bold text-gray-800 font-mono tracking-wider">{{ $pesanan->kode_pesanan }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('user.pesanan') }}" class="w-full sm:w-auto bg-green-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-green-700 transition-transform transform hover:scale-105 shadow-lg">
                    <i class="fas fa-credit-card mr-2"></i>Lanjutkan Pembayaran
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto bg-gray-100 text-gray-700 font-bold py-3 px-8 rounded-lg hover:bg-gray-200 transition">
                    Kembali ke Beranda
                </a>
            </div>

             <p class="text-xs text-gray-400 mt-8">
                Anda dapat menyelesaikan pembayaran nanti melalui halaman "Pesanan Saya" di profil Anda.
            </p>
        </div>
    </div>
</div>
@endsection
