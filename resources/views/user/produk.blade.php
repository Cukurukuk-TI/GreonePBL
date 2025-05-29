@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($produks as $produk)
        <a href="{{ route('produk.show', $produk->id) }}">
            <div class="bg-white p-4 rounded shadow hover:shadow-lg transition-all duration-200">
                <!-- Gambar -->
                <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                     alt="{{ $produk->nama_produk }}"
                     class="w-full h-48 object-cover rounded mb-2">

                <!-- Nama Produk -->
                <h3 class="text-lg font-semibold">{{ $produk->nama_produk }}</h3>

                <!-- Deskripsi Singkat -->
                <p class="text-sm text-gray-600">{{ Str::limit($produk->deskripsi_produk, 100) }}</p>

                <!-- Harga -->
                <p class="text-green-700 font-bold mt-2">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</p>

                <!-- Stok -->
                {{-- <p class="text-sm text-gray-500 mt-1">Stok: <span class="font-semibold">{{ $produk->stok_produk }}</span></p> --}}
            </div>
        </a>
    @endforeach
</div>
@endsection
