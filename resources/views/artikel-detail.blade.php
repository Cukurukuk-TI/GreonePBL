@extends('layouts.app')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <p class="text-base font-semibold text-green-600 uppercase">{{ $artikel->kategoriArtikel->nama ?? 'Umum' }}</p>
                <h1 class="mt-2 block text-3xl text-center leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $artikel->judul }}
                </h1>
                <div class="mt-4 text-center text-sm text-gray-500">
                    <span>Ditulis oleh <strong>{{ $artikel->author }}</strong></span>
                    <span class="mx-2">&middot;</span>
                    <span>Dipublikasikan pada {{ $artikel->tanggal_post->format('d F Y') }}</span>
                </div>
            </div>

            @if($artikel->gambar)
                <figure class="mb-8">
                    <img class="w-full rounded-lg shadow-lg" src="{{ asset('storage/' . $artikel->gambar) }}" alt="Gambar utama {{ $artikel->judul }}">
                </figure>
            @endif

            <div class="prose prose-lg prose-green mx-auto text-gray-600">
                {!! nl2br(e($artikel->konten)) !!}
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('artikel.public.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                    &larr; Kembali ke Semua Artikel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
