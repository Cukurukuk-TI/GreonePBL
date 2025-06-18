@extends('layouts.user')

@section('title', 'Selamat Datang di Bgd Hydrofarm')

{{-- LANGKAH UTAMA: Mengisi section 'carousel' yang sudah disiapkan di layout --}}
@section('carousel')
    <div id="main-carousel" class="splide pt-16"> {{-- pt-16 untuk memberi ruang bagi header fixed --}}
        <div class="splide__track">
            <ul class="splide__list">
                
                {{-- Slide 1, bisa diklik --}}
                <li class="splide__slide">
                    <a href="{{ route('tentang.index') }}">
                        <img src="{{ asset('img/pict1.jpg') }}" alt="Produk Hidroponik Berkualitas">
                    </a>
                </li>
                
                {{-- Slide 2, bisa diklik --}}
                <li class="splide__slide">
                     <a href="{{ route('tentang.index') }}">
                        <img src="{{ asset('img/pict2.jpg') }}" alt="Kebun Hidroponik Modern">
                    </a>
                </li>
                
                {{-- Slide 3, bisa diklik --}}
                <li class="splide__slide">
                    <a href="{{ route('tentang.index') }}">
                        <img src="{{ asset('img/pict3.jpg') }}" alt="Sayuran Segar dan Sehat">
                    </a>
                </li>

            </ul>
        </div>
    </div>
@endsection

{{-- Konten utama halaman Beranda --}}
@section('content')
    <div class="py-16 px-6 text-center">
        <h1 class="text-4xl font-bold text-brand-text">Selamat Datang di Bgd Hydrofarm</h1>
        <p class="mt-4 text-lg text-brand-text-muted">Produk segar, sehat, dan berkualitas langsung dari kebun kami.</p>
        {{-- ... konten halaman lainnya ... --}}
    </div>
@endsection

{{-- Script khusus untuk inisialisasi Splide.js --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi carousel
        if (document.getElementById('main-carousel')) {
            new Splide('#main-carousel', {
                type: 'loop',
                autoplay: true,
                interval: 4000,
                pauseOnHover: false,
                arrows: false,
                pagination: false, // Pagination (titik-titik) diaktifkan agar lebih interaktif
                drag: true,
                speed: 1200,
            }).mount();
        }
    });
</script>
@endpush