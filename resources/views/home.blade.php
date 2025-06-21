@extends('layouts.app')

@section('title', 'Selamat Datang di Bgd Hydrofarm')

{{-- Bagian Carousel (jika diperlukan) --}}
@section('carousel')
    {{-- Tambahkan carousel di sini jika ada --}}
@endsection

@section('content')
    <section class="py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-8">
                Kategori Produk
            </h2>

            @if($kategoris->isEmpty())
                <p class="text-center text-gray-500">Belum ada kategori tersedia saat ini.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($kategoris as $kategori)
                        <a href="{{ route('produk.user', ['kategori' => $kategori->slug]) }}"
                           class="group block bg-white rounded-xl overflow-hidden shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 duration-300">

                            {{-- Gambar Kategori --}}
                            <div class="h-64 w-full">
                                @if ($kategori->gambar_kategori)
                                    <img src="{{ asset('storage/' . $kategori->gambar_kategori) }}"
                                         alt="{{ $kategori->nama_kategori }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image fa-4x"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Info Kategori --}}
                            <div class="p-5 text-center">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $kategori->nama_kategori }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $kategori->produks_count ?? 0 }} Produk
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@endsection
