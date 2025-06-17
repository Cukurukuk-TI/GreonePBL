@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-5">
    <h1 class="text-3xl font-bold text-center mb-10">Kategori Produk</h1>

    @if($kategoris->isEmpty())
        <p class="text-gray-500 text-center">Belum ada kategori tersedia.</p>
    @else
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($kategoris as $kategori)
                <a href="{{route('produk.user')}}"
                   class="w-80 bg-white shadow-md rounded-xl overflow-hidden transform transition duration-150 hover:scale-105 hover:shadow-xl">
                    
                    @if ($kategori->gambar_kategori)
                        <img src="{{ asset('storage/' . $kategori->gambar_kategori) }}"
                             alt="{{ $kategori->nama_kategori }}"
                             class="w-full h-52 object-cover group-hover:opacity-90 transition duration-300">
                    @else
                        <div class="w-full h-52 bg-gray-200 flex items-center justify-center text-gray-500">
                            Tidak Ada Gambar
                        </div>
                    @endif

                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800 group-hover:text-green-600">
                            {{ $kategori->nama_kategori }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($kategori->deskripsi, 70) }}</p>
                        <p class="text-sm font-medium text-gray-700">
                            Jumlah Produk: {{ $kategori->produks_count ?? 0 }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
