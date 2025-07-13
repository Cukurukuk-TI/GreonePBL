@extends('layouts.app') {{-- UBAH INI dari appnoslider ke app --}}

{{-- Section Carousel Baru untuk Halaman Produk --}}
@section('carousel')
    @if(isset($produkUnggulan) && $produkUnggulan->count() > 0)
    <section id="produk-carousel" class="splide" aria-label="Produk Unggulan">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach($produkUnggulan as $unggulan)
                <li class="splide__slide relative">
                    <img src="{{ asset('storage/' . $unggulan->gambar_produk) }}" alt="{{ $unggulan->nama_produk }}" class="w-full h-64 md:h-80 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end p-6">
                        <div>
                            <span class="text-white bg-green-600 px-2 py-1 text-xs font-bold rounded mb-2 inline-block">PRODUK UNGGULAN</span>
                            <h3 class="text-white text-2xl font-bold">{{ $unggulan->nama_produk }}</h3>
                            <a href="{{ route('produk.show', $unggulan->id) }}" class="mt-2 inline-block text-white font-semibold border-b-2 border-green-400 hover:bg-green-500 hover:border-green-500 px-2 py-1 transition">
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif
@endsection

@section('content')
<div class="container mx-auto px-4 pb-12">
    <div class="my-8 p-6 bg-white rounded-lg shadow-sm">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-800">
                    Jelajahi Produk Kami
                </h1>
                <p class="mt-1 text-gray-600">
                    Menampilkan: <span class="font-semibold text-green-600">{{ $nama_kategori }}</span>
                </p>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-64 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">
                    <span>Pilih Kategori</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'transform rotate-180': open}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-20" x-cloak>
                    <a href="{{ route('produk.user') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Semua Produk</a>
                    @foreach ($kategoris as $kategori)
                        <a href="{{ route('produk.kategori', $kategori->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">{{ $kategori->nama_kategori }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

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
                        <a href="{{ route('produk.show', $produk->id) }}">
                            <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                                alt="{{ $produk->nama_produk }}"
                                class="w-full h-full object-cover transition duration-500 hover:scale-105">
                        </a>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <span class="text-xs font-semibold text-green-600 mb-1">
                            {{ $produk->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 hover:text-green-600 transition">
                            <a href="{{ route('produk.show', $produk->id) }}">{{ $produk->nama_produk }}</a>
                        </h3>
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
                                <i class="fas fa-eye mr-2"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Carousel Produk Unggulan
        var produkCarousel = document.getElementById('produk-carousel');
        if (produkCarousel) {
            new Splide('#produk-carousel', {
                type: 'loop',
                perPage: 1,
                autoplay: true,
                interval: 5000,
                pauseOnHover: true,
                arrows: true,
                pagination: true,
            }).mount();
        }
    });
</script>
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    [x-cloak] { display: none !important; }
</style>
@endpush
