@extends('layouts.appnoslider')

@section('content')
<div class="container mx-auto px-4 pt-8 pb-12">
    <!-- Header Kategori -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">
            Produk <span class="text-green-600">Kategori</span> {{ $nama_kategori ?? 'Semua' }}
        </h1>
        <p class="mt-2 text-gray-600">Menampilkan semua produk yang termasuk dalam kategori ini.</p>
        <a href="{{ route('home') }}"
           class="inline-block mt-4 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm font-medium transition shadow-sm">
            ← Kembali ke Kategori
        </a>
    </div>

    <!-- Daftar Produk -->
    @if ($produks->isEmpty())
        <div class="text-center py-16">
            <h3 class="text-lg font-medium text-gray-900">Produk tidak ditemukan</h3>
            <p class="mt-1 text-gray-500">Tidak ada produk dalam kategori ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($produks as $produk)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full">
                    <div class="relative overflow-hidden h-48 bg-gray-100">
                        <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                             alt="{{ $produk->nama_produk }}"
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                    </div>

                    <div class="p-4 flex flex-col flex-grow">
                        <span class="text-xs font-semibold text-green-600 mb-1">
                            {{ $produk->kategori->nama_kategori ?? 'Umum' }}
                        </span>

                        <h3 class="text-lg font-semibold text-gray-800 mb-2 hover:text-green-600 transition">
                            <a href="{{ route('produk.show', $produk->id) }}">{{ $produk->nama_produk }}</a>
                        </h3>

                        <div class="flex items-center mb-2">
                            @if($produk->approved_testimonis_count > 0)
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($produk->approved_testimonis_avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path></svg>
                                @endfor
                                <span class="text-xs text-gray-500 ml-2">({{ $produk->approved_testimonis_count }})</span>
                            @else
                                <span class="text-xs text-gray-400 h-4">Belum ada ulasan</span>
                            @endif
                        </div>

                        <!-- Deskripsi Singkat -->
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ $produk->deskripsi_produk }}
                        </p>

                        <div class="mt-auto">
                            <span class="text-lg font-bold text-green-600">
                                Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('produk.show', $produk->id) }}"
                               class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-3 rounded text-sm font-medium text-center transition flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
