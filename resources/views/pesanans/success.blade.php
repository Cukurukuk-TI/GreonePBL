@extends('layouts.pesanan')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Pesanan Berhasil Dibuat!</h1>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <p class="text-lg font-semibold">Kode Pesanan: {{ $pesanan->kode_pesanan }}</p>
            <p class="text-gray-600">Total Bayar: {{ $pesanan->formatted_total_harga }}</p>
        </div>
        
        <p class="text-gray-600 mb-6">
            Terima kasih! Pesanan Anda sedang diproses. Kami akan menghubungi Anda segera untuk konfirmasi pembayaran.
        </p>
        
        <div class="space-x-4">
            <a href="{{ route('produk.user') }}" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                Kembali ke Dashboard
            </a>
            <a href="{{ route('produk.user') }}" 
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                Belanja Lagi
            </a>
        </div>
    </div>
</div>
@endsection