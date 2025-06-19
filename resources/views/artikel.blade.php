@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900">Artikel & Edukasi</h1>
            <p class="mt-4 text-lg text-gray-500">Temukan tips, panduan, dan informasi menarik seputar hidroponik.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($artikels as $artikel)
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        {{-- Link ke halaman detail (akan kita buat di commit 2) --}}
                        <a href="#">
                            <img class="h-48 w-full object-cover"
                                 src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : 'https://placehold.co/600x400/e2e8f0/64748b?text=BGD+Hydrofarm' }}"
                                 alt="Gambar {{ $artikel->judul }}">
                        </a>
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-600">
                                {{-- Menampilkan kategori artikel --}}
                                <span class="inline-block bg-green-100 rounded-full px-3 py-1 text-xs font-semibold text-green-800 mr-2 mb-2">
                                    {{ $artikel->kategoriArtikel->nama ?? 'Umum' }}
                                </span>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 hover:text-green-700">{{ $artikel->judul }}</p>
                                <p class="mt-3 text-base text-gray-500">{{ Str::limit(strip_tags($artikel->konten), 100) }}</p>
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
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                    <p class="text-gray-500 text-lg">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $artikels->links() }}
        </div>
    </div>
</div>
@endsection
