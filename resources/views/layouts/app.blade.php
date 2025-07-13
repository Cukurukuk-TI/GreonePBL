<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    <meta name="description" content="Bgd Hydrofarm - Solusi Hidroponik Terbaik untuk Kebun Anda">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Splide CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
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

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.open {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }

        .pulse-animation {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-50">

    <!-- Header -->
    <header class="bg-green-700 text-white fixed top-0 w-full z-50 shadow-md" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <!-- Logo -->
                <h1 class="text-2xl font-bold">Bgd <span class="font-light">hydrofarm.</span></h1>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="md:hidden focus:outline-none"
                @click="mobileMenuOpen = !mobileMenuOpen"
                :aria-expanded="mobileMenuOpen"
                title="Menu Navigasi">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-6 text-white font-medium">
            <a href="/" class="hover:text-green-200">Beranda</a>
            <a href="{{ route('produk.user') }}" class="hover:text-green-200">Produk</a>
            <a href="/artikel" class="hover:text-green-200">Artikel</a>
            {{-- <a href="/kontak" class="hover:text-green-200">Kontak</a> --}}
            <a href="/tentang" class="hover:text-green-200">Tentang Kami</a>

            <!-- Keranjang dengan Badge -->
            <a href="/keranjang" class="relative flex items-center hover:text-green-200">
                <i class="fas fa-shopping-bag mr-1"></i>
                @if($uniqueProductCount > 0)
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs font-bold rounded-full px-1.5">
                        {{ $uniqueProductCount }}
                    </span>
                @endif
            </a>

            <!-- Profile -->
            <a href="/profile" class="flex items-center hover:text-green-200">
                <i class="fas fa-user mr-1"></i>
            </a>
        </nav>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden" x-show="mobileMenuOpen" x-transition x-cloak>
            <div class="pt-4 pb-4 px-6 space-y-2 bg-green-700 text-white font-medium">
                <a href="/" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Beranda</a>
                <a href="{{ route('produk.user') }}" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Produk</a>
                <a href="/artikel" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Artikel</a>
                {{-- <a href="/kontak" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Kontak</a> --}}
                <a href="/tentang" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Tentang Kami</a>

                <a href="/keranjang" class="flex items-center py-2 hover:text-green-200" @click="mobileMenuOpen = false">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    <span>Keranjang</span>
                    @if($uniqueProductCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-1.5">
                            {{ $uniqueProductCount }}
                        </span>
                    @endif
                </a>

                <a href="/profile" class="flex items-center py-2 hover:text-green-200" @click="mobileMenuOpen = false">
                    <i class="fas fa-user mr-2"></i> 
                    <span>Profil</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Carousel -->
    @php
        $path = request()->path();
        $showCarousel = !in_array($path, ['keranjang', 'profil', 'kontak', 'login', 'register']);
    @endphp

    @if ($showCarousel)
    <div id="main-carousel" class="splide pt-[56px]">
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

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600 text-sm">
            © {{ date('Y') }} Bgd Hydrofarm. All rights reserved.
        </div>
    </footer>

    <!-- Splide JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            document.querySelectorAll('[data-new]').forEach(product => {
                product.classList.add('pulse-animation');
                setTimeout(() => product.classList.remove('pulse-animation'), 6000);
            });
        });
    </script>
</body>

</html>
