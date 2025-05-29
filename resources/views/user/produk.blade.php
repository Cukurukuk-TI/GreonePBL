@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($produks as $produk)
        <div class="bg-white p-4 rounded shadow">
            <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}" class="w-full h-48 object-cover rounded mb-2">
            <h3 class="text-lg font-semibold">{{ $produk->nama_produk }}</h3>
            <p class="text-sm text-gray-600">{{ Str::limit($produk->deskripsi_produk, 100) }}</p>
            <p class="text-green-700 font-bold mt-2">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</p>
        </div>
    @endforeach
</div>
@endsection
