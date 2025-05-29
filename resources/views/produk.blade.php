@extends('layouts.app') {{-- atau 'layouts.app' jika file-nya ada di resources/views/layouts --}}

@section('content')
    <div class="py-8 px-4">
        <h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>

        @if($produks->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($produks as $produk)
                    <div class="bg-white rounded-lg shadow p-4">
                        <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}"
                            class="w-full h-40 object-cover rounded mb-3">
                        <h3 class="text-lg font-semibold">{{ $produk->nama_produk }}</h3>
                        <p class="text-red-600 font-bold">Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}</p>
                        <div class="mt-3">
                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">Beli Sekarang</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada produk yang tersedia.</p>
        @endif
    </div>
@endsection
