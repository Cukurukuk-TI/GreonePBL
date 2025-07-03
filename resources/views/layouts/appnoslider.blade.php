<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom CSS --}}
    <style>
        /* Animasi pulse untuk produk baru */
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

    {{-- Header --}}
    <header class="bg-green-700 text-white fixed top-0 w-full z-50 shadow-md" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-5">
            <h1 class="text-2xl md:text-3xl font-bold">Bgd <span class="font-light">Hydrofarm</span></h1>

            {{-- Mobile Toggle --}}
            <button @click="open = !open" class="md:hidden focus:outline-none">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-6 text-white font-medium">
            <a href="/" class="hover:text-green-200">Beranda</a>
            <a href="{{ route('produk.user') }}" class="hover:text-green-200">Produk</a>
            <a href="/artikel" class="hover:text-green-200">Artikel</a>
            <a href="/kontak" class="hover:text-green-200">Kontak</a>
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

        {{-- Mobile Navigation --}}
        <nav x-show="open" x-transition x-cloak class="md:hidden bg-green-600">
        <!-- Mobile Navigation -->
        <div class="md:hidden" x-show="mobileMenuOpen" x-transition x-cloak>
            <div class="pt-4 pb-4 px-6 space-y-2 bg-green-700 text-white font-medium">
                <a href="/" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Beranda</a>
                <a href="{{ route('produk.user') }}" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Produk</a>
                <a href="/artikel" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Artikel</a>
                <a href="/kontak" class="block py-2 hover:text-green-200" @click="mobileMenuOpen = false">Kontak</a>
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
        </nav>
    </header>

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

    {{-- Javascript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @stack('scripts')

</body>
</html>
