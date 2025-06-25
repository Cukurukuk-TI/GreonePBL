@extends('layouts.appnoslider')

@section('title', 'Pesanan Diterima')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-2xl p-8 md:p-12 text-center">

        {{-- Tampilkan ikon dan pesan berdasarkan status pembayaran --}}
        @if($paymentStatus == 'success')
            {{-- Status Sukses/Lunas --}}
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
                <i class="fas fa-check-circle text-6xl text-green-500"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mt-4 text-lg">
                Terima kasih! Pesanan Anda telah kami terima dan pembayaran telah lunas. Kami akan segera memproses pesanan Anda.
            </p>
        @else {{-- Status Pending/Menunggu --}}
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100 mb-6">
                <i class="fas fa-hourglass-half text-6xl text-yellow-500"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800">Pesanan Diterima!</h1>
            <p class="text-gray-600 mt-4 text-lg">
                Pesanan Anda telah berhasil dibuat. Silakan selesaikan pembayaran Anda sesuai instruksi yang telah diberikan.
            </p>
        @endif

        <div class="mt-8 border-t border-gray-200 pt-8">
            <h2 class="text-xl font-bold text-gray-700">Kode Pesanan Anda:</h2>
            <p class="mt-2 text-2xl font-mono tracking-widest bg-gray-100 text-green-600 py-3 px-4 rounded-lg inline-block">
                {{ $pesanan->kode_pesanan }}
            </p>
        </div>

        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('user.pesanan') }}" class="w-full sm:w-auto bg-green-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-green-700 transition-transform transform hover:scale-105">
                <i class="fas fa-receipt mr-2"></i>Lihat Riwayat Pesanan
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-lg hover:bg-gray-300 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
