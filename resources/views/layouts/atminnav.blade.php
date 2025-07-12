<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Bgd Hydrofarm</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS & JS Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'brand-green': '#005E25',
                        'brand-green-dark': '#004d1f',
                        'brand-green-light': '#D4F4E2',
                        'brand-text': '#374151',
                        'brand-text-muted': '#6B7280',
                    }
                }
            }
        };
    </script>

    <!-- Custom Styles -->
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
        }
        [x-cloak] { 
            display: none !important; 
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    @php
        use App\Models\Testimoni;
        $jumlahTestimoniBaru = Testimoni::count();
    @endphp

<!-- Header Section -->
<header class="fixed top-0 left-0 right-0 z-40 bg-brand-green text-white shadow-md w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Left Side: Logo & Sidebar Toggle -->
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden mr-4 text-white focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <span class="text-lg font-bold">BGD Hydrofarm Admin</span>
            </div>

            <!-- Right Side: Notification & Profile -->
            <div class="flex items-center space-x-6">

                <!-- Notification Bell -->
                <div class="relative" x-data="{ showNotif: false }">
                    <button @click="showNotif = !showNotif" class="relative focus:outline-none">
                        <i class="fas fa-bell text-white text-lg"></i>
                        @if($jumlahTestimoniBaru > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1">
                                {{ $jumlahTestimoniBaru }}
                            </span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div 
                        x-show="showNotif" 
                        @click.away="showNotif = false" 
                        x-transition 
                        x-cloak 
                        class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg z-50"
                    >
                        <div class="flex items-center justify-between px-4 py-3 border-b">
                            <span class="font-semibold text-gray-800">Notifikasi Baru</span>
                            <span class="text-xs text-gray-500">Testimoni</span>
                        </div>

                        <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100 text-sm">
                            @if($jumlahTestimoniBaru > 0)
                                <li class="px-4 py-3 hover:bg-gray-50 transition">
                                    <a href="{{ route('admin.testimoni.index') }}" class="flex items-start gap-3">
                                        <div class="bg-green-100 text-green-600 rounded-full p-2">
                                            <i class="fas fa-comment-dots"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                <span class="font-semibold">{{ $jumlahTestimoniBaru }}</span> testimoni baru dari pelanggan
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @else
                                <li class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada testimoni baru saat ini
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=fff&color=005E25&size=32"
                             alt="Avatar"
                             class="w-8 h-8 rounded-full">
                    </button>
                    <div 
                        x-show="dropdownOpen" 
                        @click.away="dropdownOpen = false" 
                        x-transition 
                        x-cloak 
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 text-brand-text"
                    >
                        <a href="{{ route('admin.profile.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            Profil Admin
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

    <!-- Main Layout -->
    <div class="flex pt-16 min-h-screen">
        
        <!-- Desktop Sidebar -->
        <aside class="w-64 bg-white hidden md:block sticky top-16 h-[calc(100vh-4rem)] shadow-md p-6">
            <!-- Profile Section -->
            <div class="flex flex-col items-center text-center border-b border-gray-200 pb-6 mb-6">
                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
                         alt="Foto Profil" 
                         class="w-24 h-24 rounded-full object-cover mb-4 border-4 border-brand-green-light">
                @else
                    <div class="w-24 h-24 rounded-full bg-brand-green text-white flex items-center justify-center mb-4 text-4xl font-bold">
                        <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                @endif
                <h3 class="font-bold text-lg text-brand-text">{{ Auth::user()->name }}</h3>
                <p class="text-sm text-brand-text-muted">{{ Auth::user()->email }}</p>
            </div>

            <!-- Navigation Menu -->
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.profile.index') }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 
                           {{ request()->routeIs('admin.profile.index') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100 hover:text-brand-text' }}">
                            <i class="fas fa-user-circle w-6 text-center"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.dashboard')}}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 
                           {{ request()->routeIs('admin.dashboard') ? 'bg-brand-green-light text-brand-green font-semibold' : 'text-brand-text-muted hover:bg-gray-100 hover:text-brand-text' }}">
                            <i class="fas fa-home w-6 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="border-t border-gray-200 pt-2 mt-2">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" 
                                    onclick="confirmLogout()" 
                                    class="flex items-center gap-3 w-full text-left px-4 py-2.5 rounded-lg text-red-500 hover:bg-red-50 font-medium transition-colors">
                                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-cloak 
             class="fixed inset-0 bg-black opacity-50 z-20 md:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 bg-gray-50">
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="text-center text-sm text-gray-500 p-4 bg-gray-100 border-t">
                &copy; {{ date('Y') }} Bgd Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')

    <!-- Logout Confirmation Script -->
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
</body>
</html>