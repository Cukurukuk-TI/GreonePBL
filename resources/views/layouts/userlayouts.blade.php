<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bgd Hydrofarm')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">
    
<!-- Header -->
<header class="bg-green-700 text-white fixed top-0 w-full z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <!-- Sidebar Toggle Button (Desktop & Mobile) -->
            @auth
            <button @click="sidebarOpen = !sidebarOpen"
                class="mr-4 bg-white text-green-700 p-2 rounded-full shadow hover:bg-green-600 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            @endauth


            <!-- Logo -->
            <h1 class="text-2xl font-bold">Bgd <span class="font-light">hydrofarm.</span></h1>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <button class="md:hidden focus:outline-none" @click="mobileMenuOpen = !mobileMenuOpen">
            <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-6 text-white font-medium">
            <a href="/" class="hover:text-green-200">Beranda</a>
            <a href="{{ route('produk.user') }}" class="hover:text-green-200">Produk</a>
            <a href="/artikel" class="hover:text-green-200">Artikel</a>
            <a href="/kontak" class="hover:text-green-200">Kontak</a>
            <a href="/tentang" class="hover:text-green-200">Tentang Kami</a>
            <a href="/keranjang" class="flex items-center hover:text-green-200">
                <i class="fas fa-shopping-bag mr-1"></i>
            </a>
            <a href="/profile" class="flex items-center hover:text-green-200">
                <i class="fas fa-user mr-1"></i>
            </a>
        </nav>
    </div>

    <!-- Mobile Navigation -->
    <div class="md:hidden" x-show="mobileMenuOpen" x-transition x-cloak>
        <div class="pt-4 pb-4 px-6 space-y-2 bg-green-700 text-white font-medium">
            <a href="/" class="block py-2 hover:text-green-200">Beranda</a>
            <a href="{{ route('produk.user') }}" class="block py-2 hover:text-green-200">Produk</a>
            <a href="/artikel" class="block py-2 hover:text-green-200">Artikel</a>
            <a href="/kontak" class="block py-2 hover:text-green-200">Kontak</a>
            <a href="/tentang" class="block py-2 hover:text-green-200">Tentang Kami</a>
            <a href="/keranjang" class="flex items-center py-2 hover:text-green-200">
                <i class="fas fa-shopping-bag mr-2"></i> <span>Keranjang</span>
            </a>
            <a href="/profile" class="flex items-center py-2 hover:text-green-200">
                <i class="fas fa-user mr-2"></i> <span>Profil</span>
            </a>
        </div>
    </div>
</header>

    <div class="flex pt-16"> <!-- pt-16 to account for fixed header -->
        
        <!-- Sidebar (hanya tampil jika user login) -->
        @auth
        <!-- Desktop Sidebar -->
        <div class="hidden md:block transition-all duration-300 ease-in-out" 
             :class="sidebarOpen ? 'w-60' : 'w-0'">
            <div class="w-60 bg-white border-r border-gray-300 p-4 pt-4 h-screen overflow-y-auto"
                 x-show="sidebarOpen" x-transition>
                <!-- Profile Info -->
                <div class="flex flex-col items-center mb-8">
                    @if (auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile Photo"
                             class="w-24 h-24 rounded-full object-cover mb-4">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                            <span class="text-3xl text-gray-500">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif

                    <h3 class="font-medium text-lg">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                </div>

                <!-- Navigation -->
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('profile.content') }}"
                               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.content') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}">
                                <i class="fas fa-user-circle mr-3"></i>
                                <span>My Account</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('alamat.index') }}"
                               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('alamat.*') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}">
                                <i class="fas fa-map-marker-alt mr-3"></i>
                                <span>Address</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.pesanan') }}"
                               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('user.pesanan') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}">
                                <i class="fas fa-shopping-bag mr-3"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li>
                            <div onclick="showLogoutModal()"
                                 class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-100 cursor-pointer">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40" 
             x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             x-cloak>
        </div>

        <!-- Mobile Sidebar -->
        <div class="md:hidden fixed left-0 top-16 bottom-0 w-60 bg-white border-r border-gray-300 z-50 transform transition-transform duration-300 ease-in-out"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             x-cloak>
            <div class="p-4 h-full overflow-y-auto">
                <!-- Profile Info -->
                <div class="flex flex-col items-center mb-8">
                    @if (auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile Photo"
                             class="w-24 h-24 rounded-full object-cover mb-4">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                            <span class="text-3xl text-gray-500">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif

                    <h3 class="font-medium text-lg">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                </div>

                <!-- Navigation -->
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('profile.content') }}"
                               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.content') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}"
                               @click="sidebarOpen = false">
                                <i class="fas fa-user-circle mr-3"></i>
                                <span>MyAccount</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('alamat.index') }}"
                               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('alamat.*') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}"
                               @click="sidebarOpen = false">
                                <i class="fas fa-map-marker-alt mr-3"></i>
                                <span>Address</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.pesanan') }}"
                               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('user.pesanan') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}"
                               @click="sidebarOpen = false">
                                <i class="fas fa-shopping-bag mr-3"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li>
                            <div onclick="showLogoutModal()"
                                 class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-100 cursor-pointer">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Logout Modal -->
        <div id="logout-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div id="logout-modal-content"
                 class="bg-white rounded-lg shadow-lg p-6 transform scale-95 opacity-0 transition-all duration-300 max-w-md w-full mx-4">
                <h2 class="text-xl font-semibold mb-4 text-center">Konfirmasi Logout</h2>
                <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="document.getElementById('logout-form').submit()"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Ya, Logout
                    </button>
                    <button onclick="hideLogoutModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
        </form>
        @endauth

        <!-- Main Content -->
<main class="flex-1 px-6 py-6">
    @yield('content')
</main>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6 @auth {{ 'md:' . (request()->routeIs('profile.*') || request()->routeIs('alamat.*') || request()->routeIs('user.pesanan') ? 'ml-0' : 'ml-0') }} @endauth">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-600">
            <p>© 2025 Bgd Hydrofarm. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function showLogoutModal() {
            const modal = document.getElementById('logout-modal');
            const content = document.getElementById('logout-modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logout-modal');
            const content = document.getElementById('logout-modal-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.querySelector('[x-show="mobileMenuOpen"]');
            const toggleButton = document.querySelector('[\\@click="mobileMenuOpen = !mobileMenuOpen"]');
            
            if (mobileMenu && !mobileMenu.contains(event.target) && !toggleButton.contains(event.target)) {
                // Close mobile menu logic handled by Alpine.js
            }
        });

        // Close sidebar when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Reset Alpine.js data
                document.querySelector('[x-data]').__x.$data.sidebarOpen = false;
                document.querySelector('[x-data]').__x.$data.mobileMenuOpen = false;
            }
        });
    </script>
</body>
</html>