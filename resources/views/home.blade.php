@extends('layouts.app')

@section('content')
<div class="space-y-16 py-5">

    {{-- Bagian Kategori Produk --}}
    <section class="container mx-auto px-4">
        {{-- Header Section --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Kategori Produk</h2>
            <a href="{{ route('produk.user') }}" class="text-green-600 font-semibold hover:text-green-800 transition-colors">
                Lihat Semua Produk &rarr;
            </a>
        </div>

        {{-- Grid Kategori --}}
        @if($kategoris->isEmpty())
            <p class="text-gray-500 text-center">Belum ada kategori tersedia.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($kategoris as $kategori)
                    <a href="{{ route('produk.kategori', $kategori->id) }}" class="group block bg-white shadow-lg rounded-xl overflow-hidden transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="relative">
                            @if ($kategori->gambar_kategori)
                                <img src="{{ asset('storage/' . $kategori->gambar_kategori) }}" alt="{{ $kategori->nama_kategori }}" class="w-full h-56 object-cover">
                            @else
                                <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-leaf text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 transition-all duration-300"></div>
                            <div class="absolute bottom-0 left-0 p-4">
                                <h3 class="text-xl font-bold text-white shadow-sm">{{ $kategori->nama_kategori }}</h3>
                                <p class="text-sm text-green-200">{{ $kategori->produks_count ?? 0 }} Produk</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Bagian Artikel Terbaru --}}
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            {{-- Header Section --}}
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Artikel & Edukasi</h2>
                <a href="{{ route('artikel.public.index') }}" class="text-green-600 font-semibold hover:text-green-800 transition-colors">
                    Lihat Semua Artikel &rarr;
                </a>
            </div>

            {{-- Grid Artikel --}}
            @if($artikels->isEmpty())
                <p class="text-gray-500 text-center">Belum ada artikel yang dipublikasikan.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($artikels as $artikel)
                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white">
                            <a href="{{ route('artikel.public.show', $artikel->slug) }}" class="flex-shrink-0">
                                <img class="h-48 w-full object-cover"
                                     src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : 'https://placehold.co/600x400/e2e8f0/64748b?text=BGD+Hydrofarm' }}"
                                     alt="Gambar {{ $artikel->judul }}">
                            </a>
                            <div class="flex-1 p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-green-600">
                                        <span class="inline-block bg-green-100 rounded-full px-3 py-1 text-xs font-semibold text-green-800 mr-2 mb-2">
                                            {{ $artikel->kategoriArtikel->nama ?? 'Umum' }}
                                        </span>
                                    </p>
                                    <a href="{{ route('artikel.public.show', $artikel->slug) }}" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900 hover:text-green-700">{{ Str::limit($artikel->judul, 50) }}</p>
                                    </a>
                                </div>
                                <div class="mt-6 flex items-center">
                                    <div class="text-sm text-gray-500">
                                        <span>{{ $artikel->author }}</span>
                                        <span class="mx-1">&middot;</span>
                                        <span>{{ $artikel->tanggal_post->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</div>
@endsection
