<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Splide CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom Style --}}
    <style>
        .splide__slide img {
            width: 100%;
            height: 50vh;
            object-fit: cover;
        }

        @media (min-width: 768px) {
            .splide__slide img {
                height: 60vh;
            }
        }

        /* Animasi untuk menu mobile */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.open {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }

        /* Animasi produk baru */
        .pulse-animation {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-50">

    {{-- Header dengan perbaikan menu mobile --}}
    <header class="bg-green-700 text-white fixed top-0 w-full z-50 shadow-md" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-5">
            <h1 class="text-2xl md:text-3xl font-bold">Bgd <span class="font-light">Hydrofarm</span></h1>

            {{-- Mobile Menu Toggle --}}
            <button @click="open = !open" 
                    class="md:hidden focus:outline-none p-2 -mr-2"
                    :aria-expanded="open">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Desktop Menu --}}
            <nav class="hidden md:flex space-x-6 font-medium">
                <a href="/" class="hover:text-green-300">Beranda</a>
                <a href="{{ route('produk.user') }}" class="hover:text-green-300">Produk</a>
                <a href="/artikel" class="hover:text-green-300">Artikel</a>
                <a href="/kontak" class="hover:text-green-300">Kontak</a>
                <a href="/tentang" class="hover:text-green-300">Tentang Kami</a>
                <a href="/keranjang"><i class="fas fa-shopping-bag"></i></a>
                <a href="/profile"><i class="fas fa-user"></i></a>
            </nav>
        </div>

        {{-- Mobile Menu Dropdown dengan animasi --}}
        <nav x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-screen"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-screen"
             x-transition:leave-end="opacity-0 max-h-0"
             x-cloak
             class="md:hidden bg-green-600 overflow-hidden">
            <div class="flex flex-col space-y-2 px-4 py-2 text-white font-medium">
                <a href="/" class="py-2 hover:text-green-200" @click="open = false">Beranda</a>
                <a href="{{ route('produk.user') }}" class="py-2 hover:text-green-200" @click="open = false">Produk</a>
                <a href="/artikel" class="py-2 hover:text-green-200" @click="open = false">Artikel</a>
                <a href="/kontak" class="py-2 hover:text-green-200" @click="open = false">Kontak</a>
                <a href="/tentang" class="py-2 hover:text-green-200" @click="open = false">Tentang Kami</a>
                <a href="/keranjang" class="py-2 hover:text-green-200" @click="open = false"><i class="fas fa-shopping-bag mr-1"></i> Keranjang</a>
                <a href="/profile" class="py-2 hover:text-green-200" @click="open = false"><i class="fas fa-user mr-1"></i> Profil</a>
            </div>
        </nav>
    </header>

    {{-- Carousel --}}
    @php
        $path = request()->path();
        $showCarousel = !in_array($path, ['keranjang', 'profil', 'kontak', 'login', 'register']);
    @endphp

    @if ($showCarousel)
    <div id="main-carousel" class="splide pt-[72px]">
        <div class="splide__track">
            <ul class="splide__list">
                <li class="splide__slide">
                    <img src="{{ asset('img/pict1.jpg') }}" alt="Produk Hidroponik">
                </li>
                <li class="splide__slide">
                    <img src="{{ asset('img/pict2.jpg') }}" alt="Kebun Hidroponik">
                </li>
                <li class="splide__slide">
                    <img src="{{ asset('img/pict3.jpg') }}" alt="Sayuran Segar">
                </li>
            </ul>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-grow py-20 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600 text-sm">
            © {{ date('Y') }} Bgd Hydrofarm. All rights reserved.
        </div>
    </footer>

    {{-- Splide JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi carousel hanya jika ada
            const carousel = document.getElementById('main-carousel');
            if (carousel) {
                new Splide('#main-carousel', {
                    type: 'loop',
                    autoplay: true,
                    interval: 4000,
                    pauseOnHover: false,
                    arrows: false,
                    pagination: false,
                    speed: 1000,
                    perPage: 1,
                    drag: true,
                    rewind: true
                }).mount();
            }

            // Hover effect untuk card kategori
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-8px)';
                    card.style.boxShadow = '0 12px 24px rgba(0,0,0,0.15)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
            });

            // Efek pulse untuk produk baru
            document.querySelectorAll('[data-new]').forEach(product => {
                product.classList.add('pulse-animation');
                setTimeout(() => product.classList.remove('pulse-animation'), 6000);
            });
        });
    </script>
</body>
</html>