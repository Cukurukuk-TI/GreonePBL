@extends('layouts.appnoslider')

@section('content')
<div class="container mx-auto px-4 pt-8 pb-12">
    <!-- Header dengan Filter dan Pencarian -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">
            <span class="text-green-600">Produk</span> Kami
        </h1>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="relative flex-grow">
                <input type="text" placeholder="Cari produk..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Info Filter Aktif -->
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg hidden" id="filter-info">
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
    @if ($produks->isEmpty())
        <div class="text-center py-16">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Produk tidak ditemukan</h3>
            <p class="mt-1 text-gray-500">Coba gunakan kata kunci atau filter yang berbeda.</p>
            <div class="mt-6">
                <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Reset Pencarian
                </button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($produks as $produk)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full">
                    <!-- Badge Promo -->
                    @if($produk->promo)
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                            -{{ $produk->diskon }}%
                        </div>
                    @endif
                    
                    <!-- Gambar Produk -->
                    <div class="relative overflow-hidden h-48 bg-gray-100">
                        <img src="{{ asset('storage/' . $produk->gambar_produk) }}" 
                             alt="{{ $produk->nama_produk }}"
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                        
                        <!-- Quick Actions (Muncul saat hover) -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <button class="bg-white rounded-full p-2 mx-1 transform translate-y-3 group-hover:translate-y-0 transition duration-300 hover:bg-green-100" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button class="bg-white rounded-full p-2 mx-1 transform translate-y-3 group-hover:translate-y-0 transition duration-300 hover:bg-green-100 delay-75" title="Tambah ke Wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <button class="bg-white rounded-full p-2 mx-1 transform translate-y-3 group-hover:translate-y-0 transition duration-300 hover:bg-green-100 delay-100" title="Tambah ke Keranjang">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Konten Produk -->
                    <div class="p-4 flex flex-col flex-grow">
                        <!-- Kategori -->
                        <span class="text-xs font-semibold text-green-600 mb-1">
                            {{ $produk->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        
                        <!-- Nama Produk -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 hover:text-green-600 transition">
                            <a href="{{ route('produk.show', $produk->id) }}">{{ $produk->nama_produk }}</a>
                        </h3>
                        
                        <!-- Deskripsi Singkat -->
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ $produk->deskripsi_produk }}
                        </p>
                        
                        <!-- Harga -->
                        <div class="mt-auto">
                            @if($produk->promo)
                                <div class="flex items-center">
                                    <span class="text-lg font-bold text-green-600">
                                        Rp{{ number_format($produk->harga_diskon, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-500 line-through ml-2">
                                        Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-lg font-bold text-green-600">
                                    Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                        
                    <!-- Tombol Aksi -->
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        {{-- Tombol Detail --}}
                        <a href="{{ route('produk.show', $produk->id) }}" 
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-3 rounded text-sm font-medium text-center transition flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>

                        {{-- Tombol Tambah ke Keranjang --}}
                        <form action="{{ route('keranjang.store') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded text-sm font-medium transition flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{-- <div class="mt-8">
            {{ $produks->links() }}
        </div> --}}
    @endif
</div>

<!-- JavaScript untuk Filter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.querySelector('select');
    const searchInput = document.querySelector('input[type="text"]');
    const filterInfo = document.getElementById('filter-info');
    const filterText = document.getElementById('filter-text');
    const resetFilter = document.getElementById('reset-filter');
    
    // Simulasikan filter (implementasi AJAX bisa ditambahkan)
    function applyFilters() {
        const searchValue = searchInput.value.trim();
        const categoryValue = categoryFilter.value;
        
        if (searchValue || categoryValue) {
            filterInfo.classList.remove('hidden');
            let filterMessage = '';
            
            if (searchValue && categoryValue) {
                const categoryName = categoryFilter.options[categoryFilter.selectedIndex].text;
                filterMessage = `"${searchValue}" dalam kategori "${categoryName}"`;
            } else if (searchValue) {
                filterMessage = `"${searchValue}"`;
            } else {
                const categoryName = categoryFilter.options[categoryFilter.selectedIndex].text;
                filterMessage = `kategori "${categoryName}"`;
            }
            
            filterText.textContent = filterMessage;
        } else {
            filterInfo.classList.add('hidden');
        }
        
        // Di sini bisa ditambahkan AJAX untuk filter real-time
    }
    
    // Event listeners
    categoryFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') applyFilters();
    });
    
    resetFilter.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        filterInfo.classList.add('hidden');
        // Reset tampilan produk (AJAX bisa ditambahkan)
    });
});
</script>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .group:hover .group-hover\:bg-opacity-20 {
        background-opacity: 0.2;
    }
</style>
@endsection