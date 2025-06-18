<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Bgd Hydrofarm')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'brand-green': '#005E25',
                        'brand-green-light': '#D4F4E2',
                        'brand-text': '#374151',
                        'brand-text-muted': '#6B7280',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'poppins', sans-serif; }
        /* Cloak untuk AlpineJS agar tidak 'flicker' saat loading */
        [x-cloak] { display: none !important; }

        /* carousel */
            body { font-family: 'poppins', sans-serif; }
    [x-cloak] { display: none !important; }

    /* TAMBAHKAN INI */
    .splide__slide a {
        display: block;
        width: 100%;
        height: 100%;
    }
    .splide__slide img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Memastikan gambar mengisi slide tanpa distorsi */
    }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-50 text-brand-text">

    <header class="bg-brand-green text-white fixed top-0 w-full z-50 shadow-lg" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="#" class="text-2xl font-bold"> {{-- href="{{ route('home') }}" --}}
                    Bgd<span class="font-light">hydrofarm</span>
                </a>

                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
                    <a href="{{ route('home.index') }}" class="hover:text-green-200 transition-colors">Beranda</a> {{-- href="{{ route('home') }}" --}}
                    <a href="{{ route('produk.index') }}" class="hover:text-green-200 transition-colors">Produk</a> {{-- href="{{ route('produk.user') }}" --}}
                    <a href="{{ route('artikel.index') }}" class="hover:text-green-200 transition-colors">Artikel</a> {{-- href="{{ route('artikel.user') }}" --}}
                    <a href="{{ route('kontak.index') }}" class="hover:text-green-200 transition-colors">Kontak</a> {{-- href="{{ route('kontak') }}" --}}
                    <a href="{{ route('tentang.index') }}" class="hover:text-green-200 transition-colors">Tentang Kami</a> {{-- href="{{ route('tentang') }}" --}}
                </nav>

                <div class="hidden md:flex items-center space-x-5">
                    <a href="#" class="hover:text-green-200 transition-colors"><i class="fas fa-shopping-bag fa-lg"></i></a> {{-- href="{{ route('keranjang') }}" --}}
                    <a href="#" class="hover:text-green-200 transition-colors"><i class="fas fa-user fa-lg"></i></a> {{-- href="{{ route('profil.user') }}" --}}
                </div>

                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none">
                        <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-brand-green border-t border-green-600">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 text-center">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Beranda</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Produk</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Artikel</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Kontak</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Tentang Kami</a>
                <div class="border-t border-green-600 my-3"></div>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Keranjang</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-green-600">Profil</a>
            </div>
        </div>
    </header>

    @yield('carousel')

    <main class="flex-grow">
        <div class="pt-16">
            @yield('content')
        </div>
    </main>

    <footer class="bg-gray-100 border-t">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold text-brand-text">Bgd Hydrofarm</h2>
            <p class="mt-2 text-sm text-brand-text-muted">Menyediakan sayuran hidroponik segar langsung dari kebun kami.</p>
            <div class="mt-6 flex justify-center space-x-6">
                <a href="#" class="text-brand-text-muted hover:text-brand-text"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-brand-text-muted hover:text-brand-text"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="#" class="text-brand-text-muted hover:text-brand-text"><i class="fab fa-whatsapp fa-lg"></i></a>
            </div>
            <p class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Bgd Hydrofarm. All Rights Reserved.
            </p>
        </div>
    </footer>

    @stack('scripts')

</body>
</html>