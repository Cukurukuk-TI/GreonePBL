<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .carousel-wrapper {
            padding-top: 60px;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-50">

    <!-- Header -->
    <header class="bg-green-700 text-white fixed top-0 w-full z-50 shadow-md" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Bgd <span class="font-light">hydrofarm.</span></h1>

            <!-- Toggle Button -->
            <button class="md:hidden focus:outline-none" @click="open = !open">
                <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-6 text-white font-medium">
                <a href="/" class="hover:text-green-200">Beranda</a>
                <a href="{{ route('produk.user') }}" class="hover:text-green-200">Produk</a>
                <a href="/artikel" class="hover:text-green-200">Artikel</a>
                <a href="/kontak" class="hover:text-green-200">Kontak</a>
                <a href="/tentang" class="hover:text-green-200">Tentang Kami</a>
                <a class="nav-link" href="/chart"><i class="fas fa-shopping-bag"></i></a>
                <a class="nav-link" href="/profile"><i class="fas fa-user"></i></a>
            </nav>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden" x-show="open" x-transition x-cloak>
            <div class="pt-4 space-y-2">
                <a href="/" class="block py-2 hover:text-green-600">Beranda</a>
                <a href="/produk" class="block py-2 hover:text-green-600">Produk</a>
                <a href="/artikel" class="block py-2 hover:text-green-600">Artikel</a>
                <a href="/kontak" class="block py-2 hover:text-green-600">Kontak</a>
                <a href="/tentang" class="block py-2 hover:text-green-600">Tentang Kami</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow text-center py-20 px-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-600">
            <p>© 2025 Bgd Hydrofarm. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add interactive effects to category cards
            const categoryCards = document.querySelectorAll('.category-card');

            categoryCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-10px)';
                    this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.15)';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
                });

                card.addEventListener('mousedown', function () {
                    this.style.transform = 'translateY(2px) scale(0.98)';
                });

                card.addEventListener('mouseup', function () {
                    this.style.transform = 'translateY(-10px) scale(1)';
                });
            });

            const newProducts = document.querySelectorAll('[data-new]');
            newProducts.forEach(product => {
                product.classList.add('pulse-animation');
                setTimeout(() => {
                    product.classList.remove('pulse-animation');
                }, 6000);
            });
        });
    </script>
</body>

</html>
