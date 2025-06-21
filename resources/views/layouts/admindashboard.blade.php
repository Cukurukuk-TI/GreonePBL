<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') - Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font & Ikon --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind & Alpine.js --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    colors: {
                        'brand-green': '#005E25',
                        'brand-green-dark': '#004d1f',
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
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    {{-- Header --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-brand-green text-white shadow-md w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden mr-4 text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="text-lg font-bold">BGD Hydrofarm Admin</div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="hover:text-gray-200">
                        <i class="fas fa-bell"></i>
                    </button>
                    {{-- Dropdown Avatar --}}
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=fff&color=005E25&size=32" alt="Avatar" class="w-8 h-8 rounded-full">
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 text-brand-text">
                            <a href="{{ route('admin.profile.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Profil Admin</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <button onclick="confirmLogout()" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2 w-4 text-center"></i> Keluar
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Layout --}}
    <div class="flex pt-16">
        {{-- Sidebar --}}
        <aside
            class="fixed left-0 top-0 h-full w-64 bg-white border-r z-30 pt-16 transition-transform duration-300 ease-in-out md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="p-4">
                <nav class="space-y-2 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-home w-5 mr-3"></i><span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.kategoris.index') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.kategoris.*') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-list w-5 mr-3"></i><span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.produks.index') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.produks.*') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-box w-5 mr-3"></i><span>Produk</span>
                    </a>
                    <a href="{{ route('admin.pesanans.index') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.pesanans.*') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-shopping-cart w-5 mr-3"></i><span>Pesanan</span>
                    </a>
                    <a href="{{ route('admin.promos.index') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.promos.*') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-tags w-5 mr-3"></i><span>Promo</span>
                    </a>
                    {{-- Tambahan lainnya --}}
                    <a href="#" class="flex items-center p-2 rounded-lg text-brand-text-muted hover:bg-gray-100">
                        <i class="fas fa-newspaper w-5 mr-3"></i><span>Artikel</span>
                    </a>
                    <a href="#" class="flex items-center p-2 rounded-lg text-brand-text-muted hover:bg-gray-100">
                        <i class="fas fa-comment-dots w-5 mr-3"></i><span>Testimoni</span>
                    </a>
                    <a href="#" class="flex items-center p-2 rounded-lg text-brand-text-muted hover:bg-gray-100">
                        <i class="fas fa-user-friends w-5 mr-3"></i><span>Daftar Pelanggan</span>
                    </a>
                    <a href="#" class="flex items-center p-2 rounded-lg text-brand-text-muted hover:bg-gray-100">
                        <i class="fas fa-user-shield w-5 mr-3"></i><span>Role Pengguna</span>
                    </a>
                </nav>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-20 md:hidden" x-cloak></div>

        {{-- Main --}}
        <div class="flex-1 md:ml-64">
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
            <footer class="text-center text-sm text-gray-500 p-4 bg-gray-100 border-t">
                &copy; {{ date('Y') }} Bgd Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>

    {{-- Script logout --}}
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: "Anda yakin ingin keluar dari akun Anda?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    title: 'text-brand-text',
                    htmlContainer: 'text-brand-text-muted',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
