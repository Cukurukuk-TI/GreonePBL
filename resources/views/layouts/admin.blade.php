<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Bgd Hydrofarm</title>

    <script src="https://cdn.tailwindcss.com"></script>

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
                        // Warna-warna yang diambil dari desain Anda
                        'brand-green': '#005E25',      // Hijau tua di header
                        'brand-green-light': '#D4F4E2', // Hijau muda untuk background link aktif
                        'brand-text': '#374151',      // Warna teks utama (abu-abu tua)
                        'brand-text-muted': '#6B7280', // Warna teks sekunder (abu-abu)
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

    <header class="fixed top-0 left-0 right-0 h-16 bg-brand-green text-white flex items-center justify-between px-8 z-30">
        <div class="text-2xl font-bold">
            Bgd Hydrofarm
        </div>
        <div class="flex items-center gap-6">
            <button>
                {{-- jadikan ini route ke admin.profile --}}
                <a href="{{ route('admin.profile') }}" class="text-white hover:text-gray-200 transition-colors">
                    <i class="far fa-user text-xl"></i>
                </a>
            </button>
            {{-- <img src="https://via.placeholder.com/32" alt="Avatar" class="w-8 h-8 rounded-full"> --}}
        </div>
    </header>

    <aside class="fixed top-16 left-0 w-64 h-full bg-white p-4 z-20">
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="{{route('admin.dashboard')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/dashboard*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-home fa-lg w-6 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.kategori.index')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/kategori*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-tags fa-lg w-6 text-center"></i>
                        <span>Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.produk.index')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/produk*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-box fa-lg w-6 text-center"></i>
                        <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.pesanan')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/pesanan*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-receipt fa-lg w-6 text-center"></i>
                        <span>Pesanan</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.promo')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/promo*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-percent fa-lg w-6 text-center"></i>
                        <span>Promo</span>
                    </a>
                </li>
                 <li>
                    <a href="{{route('admin.artikel')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/artikel*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-newspaper fa-lg w-6 text-center"></i>
                        <span>Artikel</span>
                    </a>
                </li>
                 <li>
                    <a href="{{route('admin.testimoni')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/testimoni*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-comment-dots fa-lg w-6 text-center"></i>
                        <span>Testimoni</span>
                    </a>
                </li>
                <hr class="my-3 border-gray-200">
                <li>
                    <a href="{{route('admin.akun-pelanggan')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/akun-pelanggan*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-user-friends fa-lg w-6 text-center"></i>
                        <span>Akun Pelanggan</span>
                    </a>
                </li>
                 <li>
                    <a href="{{route('admin.role-pengguna')}}" class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('admin/role-pengguna*') ? 'bg-brand-green-light text-brand-text font-semibold' : 'text-brand-text-muted hover:bg-gray-100' }}">
                        <i class="fas fa-user-cog fa-lg w-6 text-center"></i>
                        <span>Role Pengguna</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="ml-64 mt-16 p-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>