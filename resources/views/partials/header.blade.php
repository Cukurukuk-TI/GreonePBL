<header class="bg-green-700 text-white fixed top-0 w-full z-50 shadow-md" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Bgd <span class="font-light">hydrofarm.</span></h1>
        <button class="md:hidden focus:outline-none" @click="open = !open">
            <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <nav class="hidden md:flex space-x-6 text-white font-medium">
            <a href="/" class="hover:text-green-200">Beranda</a>
            <a href="/produk" class="hover:text-green-200">Produk</a>
            <a href="/artikel" class="hover:text-green-200">Artikel</a>
            <a href="/kontak" class="hover:text-green-200">Kontak</a>
            <a href="/tentang" class="hover:text-green-200">Tentang Kami</a>
            <a class="nav-link" href="/chart"><i class="fas fa-shopping-bag"></i></a>
            <a class="nav-link" href="/profil"><i class="fas fa-user"></i></a>
        </nav>
    </div>

    <button class="md:hidden focus:outline-none" @click="open = !open">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

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
