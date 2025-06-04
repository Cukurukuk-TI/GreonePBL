@extends('layouts.appnoslider')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Buat Pesanan</h1>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Kiri: Informasi Pesanan -->
        <div class="md:col-span-2 space-y-4">
            <!-- Alamat Pengiriman -->
            <div class="border rounded-lg p-4">
                <h2 class="font-semibold mb-2">Alamat Pengiriman</h2>
                <p class="text-sm text-gray-700">Pasar Ambacang, Kec. Kuranji, Kota Padang 25176</p>
                <a href="#" class="text-green-600 font-semibold text-sm mt-2 inline-block">Ubah Alamat Pengiriman</a>
            </div>

            <!-- Metode Pengiriman -->
            <div class="border rounded-lg p-4">
                <h2 class="font-semibold mb-2">Metode Pengiriman</h2>
                <p class="text-sm text-gray-700">SiCepat Ultimate</p>
                <a href="#" class="text-green-600 font-semibold text-sm mt-2 inline-block">Ubah Metode Pengiriman</a>
            </div>

            <!-- Promo -->
            <div class="border rounded-lg p-4">
                <h2 class="font-semibold mb-2">Promo</h2>
                <p class="text-sm text-gray-700">Diskon Rp20.000 Minimal Pembelian Rp50.000</p>
                <a href="#" class="text-green-600 font-semibold text-sm mt-2 inline-block">Ubah Promo</a>
            </div>

            <!-- Metode Pembayaran -->
            <div class="border rounded-lg p-4">
                <h2 class="font-semibold mb-2">Metode Pembayaran</h2>
                <p class="text-sm text-gray-700">BNI Virtual Account</p>
                <a href="#" class="text-green-600 font-semibold text-sm mt-2 inline-block">Ubah Metode Pembayaran</a>
            </div>
        </div>

        <!-- Kanan: Ringkasan Pesanan -->
        <div>
            <div class="border rounded-lg p-4">
                <div class="flex gap-4 mb-4">
                    <img src="https://via.placeholder.com/80" alt="Produk" class="rounded-md w-20 h-20 object-cover">
                    <div>
                        <p class="font-semibold">Selada 1 KG</p>
                        <p class="text-gray-600 text-sm">Rp25.000 x 4</p>
                    </div>
                </div>
                <div class="border-t pt-4 text-sm space-y-1 text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp100.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Ongkos Kirim</span>
                        <span>Rp10.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Pajak</span>
                        <span>Rp11.000</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>-Rp20.000</span>
                    </div>
                    <div class="flex justify-between font-bold border-t pt-2 text-black">
                        <span>Grand Total</span>
                        <span>Rp101.000</span>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="mt-4">
                <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
