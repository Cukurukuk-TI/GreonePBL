@extends('layouts.appnoslider')

@section('title', 'Produk Kami')

@section('content')
<div class="container mx-auto px-4 pt-8 pb-12">

    <!-- Header dan Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">
            Jelajahi <span class="text-green-600">Produk</span> Kami
        </h1>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-stretch md:items-center">
            <div class="relative flex-grow">
                <input type="text" id="search-input" placeholder="Cari produk..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select id="category-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ strtolower($kategori->nama_kategori) }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Info Filter Aktif -->
    <div id="filter-info" class="hidden bg-green-100 text-green-800 text-sm font-regular px-4 py-3 rounded-md mb-6 transition duration-300 ease-in-out">
        <div class="flex justify-between items-center">
            <p class="text-green-700">
                Menampilkan hasil untuk: <span class="font-semibold" id="filter-text">Semua Produk</span>
            </p>
            <button class="text-green-600 hover:text-green-800 text-sm font-medium" id="reset-filter">
                Reset Filter
            </button>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div id="produk-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ($produks as $produk)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl hover:scale-100 transition-all duration-300 transform overflow-hidden flex flex-col p-4"
                 data-nama="{{ strtolower($produk->nama_produk) }}"
                 data-kategori="{{ strtolower($produk->kategori->nama_kategori ?? 'umum') }}">

                <!-- Gambar rasio 1:1 -->
                <div class="w-full aspect-square bg-gray-200 flex items-center justify-center overflow-hidden rounded-lg mb-4">
                    <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                         alt="{{ $produk->nama_produk }}"
                         class="object-cover w-full h-full">
                </div>

                <!-- Nama dan Kategori rata kiri -->
                <div class="text-left">
                    <h3 class="text-lg font-bold text-gray-800">{{ $produk->nama_produk }}</h3>
                    <p class="text-sm font-medium text-gray-600 mt-1">{{ $produk->kategori->nama_kategori ?? 'Umum' }}</p>
                </div>

                <!-- Harga -->
                <div class="text-left mt-3">
                    @if ($produk->promo)
                        <div class="text-orange-500 text-xl font-bold">
                            Rp{{ number_format($produk->harga_diskon, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-400 line-through">
                            Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-orange-500 text-xl font-bold">
                            Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                <!-- Tombol -->
                <div class="grid grid-cols-2 gap-2 mt-4">
                        <a href="{{ route('produk.show', $produk->id) }}"
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 px-4 rounded text-sm flex justify-center items-center transition shadow-sm">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                                      9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>

                        <form action="{{ route('keranjang.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded text-sm flex justify-center items-center transition shadow-sm w-full">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 
                                          1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 
                                          0 2 2 0 014 0z" />
                                </svg>
                                Tambah
                            </button>
                        </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
</style>

<!-- JavaScript Filter -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const produkCards = document.querySelectorAll('#produk-list > div');
    const filterInfo = document.getElementById('filter-info');
    const filterText = document.getElementById('filter-text');
    const resetFilter = document.getElementById('reset-filter');

    function applyFilters() {
        const keyword = searchInput.value.trim().toLowerCase();
        const selectedCategory = categoryFilter.value;

        let shownCount = 0;

        produkCards.forEach(card => {
            const nama = card.dataset.nama;
            const kategori = card.dataset.kategori;
            const matchKeyword = nama.includes(keyword);
            const matchKategori = selectedCategory === "" || kategori === selectedCategory;

            if (matchKeyword && matchKategori) {
                card.style.display = 'flex';
                shownCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (keyword || selectedCategory) {
            filterInfo.classList.remove('hidden');
            filterText.textContent = (keyword ? `"${keyword}" ` : '') + 
                                     (selectedCategory ? ` "${categoryFilter.options[categoryFilter.selectedIndex].text}"` : '');
        } else {
            filterInfo.classList.add('hidden');
        }
    }

    searchInput.addEventListener('keyup', function (e) {
        if (e.key === 'Enter') applyFilters();
    });

    categoryFilter.addEventListener('change', applyFilters);

    resetFilter.addEventListener('click', function () {
        searchInput.value = '';
        categoryFilter.value = '';
        filterInfo.classList.add('hidden');
        produkCards.forEach(card => card.style.display = 'flex');
    });
});
</script>
@endsection
