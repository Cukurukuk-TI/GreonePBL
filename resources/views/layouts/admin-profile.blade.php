<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Bgd Hydrofarm</title>

    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js diperlukan untuk fungsionalitas dropdown --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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
        body {
            font-family: 'poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    <header class="fixed top-0 left-0 right-0 h-16 bg-brand-green text-white flex items-center justify-between px-4 sm:px-8 z-30 shadow-md">
        <div class="text-xl sm:text-2xl font-bold">
            Bgd Hydrofarm
        </div>
        {{-- home --}}
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-home text-xl"></i>
            </a>
        </div>

        <div class="flex items-center gap-4 sm:gap-6">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="block">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=fff&color=005E25&size=32" alt="Avatar" class="w-8 h-8 rounded-full">
                </button>

                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-40 origin-top-right"
                    style="display: none;"
                >
                    <a href="{{-- route('admin.profile') --}}" class="block px-4 py-2 text-sm text-brand-text hover:bg-gray-100">
                        <i class="fas fa-user-circle w-5 mr-2"></i>Profil Saya
                    </a>
                    
                    <div class="border-t border-gray-100"></div>

                    <form method="POST" action="{{-- route('logout') --}}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-5 mr-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="mt-16 p-4 sm:p-6 lg:p-8">
        {{-- PERBAIKAN UTAMA: Class 'ml-64' telah dihapus dari sini --}}
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>