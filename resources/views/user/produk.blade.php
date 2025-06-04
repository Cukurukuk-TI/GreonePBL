@extends('layouts.appnoslider')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>

    @if ($produks->isEmpty())
        <div class="text-center text-gray-500 text-lg py-10">
            Belum ada produk yang tersedia.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($produks as $produk)
                <a href="{{ route('produk.show', $produk->id) }}" class="group block">
                    <div
                        class="bg-white p-4 rounded shadow transition-all duration-300
                            relative flex flex-col h-[320px]
                            hover:shadow-[0_0_15px_5px_rgba(34,197,94,0.5)]">

                        <!-- Gambar -->
                        <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}"
                            class="w-full h-40 object-cover rounded mb-2 flex-shrink-0">

                        <!-- Nama Produk -->
                        <h3 class="text-lg font-semibold mb-1">{{ $produk->nama_produk }}</h3>

                        <!-- Deskripsi Singkat -->
                        <p class="text-sm text-gray-600">
                            {{ Str::limit($produk->deskripsi_produk, 80) }}
                        </p>

                        <!-- Harga -->
                        <p class="text-green-700 font-bold mt-1">
                            Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                        </p>

                        <!-- Tombol aksi -->
                        <div
                            class="absolute bottom-4 left-4 right-4 flex justify-between space-x-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white bg-opacity-90 p-2 rounded">
                            <a href="{{ route('pesanan.create') }}">
                                <button class="bg-green-600 text-white py-1 rounded hover:bg-green-700 w-full">
                                    Beli Sekarang
                                </button>
                            </a>
                            <button class="bg-yellow-500 text-white py-1 rounded hover:bg-yellow-600 w-full">
                                Masukkan Keranjang
                            </button>
                        </div>

                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
