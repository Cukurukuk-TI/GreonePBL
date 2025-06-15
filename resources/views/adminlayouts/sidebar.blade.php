<aside class="w-58 bg-white border-r min-h-screen p-4 pt-20">
    <nav class="space-y-2">

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center p-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-green-100' }}">
            <i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span>
        </a>

        <a href="{{ route('admin.produks.index') }}"
            class="flex items-center p-2 rounded {{ request()->routeIs('admin.produks.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-green-100' }}">
            <i class="fas fa-box w-5"></i><span class="ml-3">Produk</span>
        </a>

        <a href="{{ route('admin.kategoris.index') }}"
            class="flex items-center p-2 rounded {{ request()->routeIs('admin.kategoris.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-green-100' }}">
            <i class="fas fa-list w-5"></i><span class="ml-3">Kategori</span>
        </a>

        <a href="#" class="flex items-center p-2 rounded hover:bg-green-100">
            <i class="fas fa-shopping-cart w-5"></i><span class="ml-3">Pesanan</span>
        </a>
        {{-- <a href="{{ route('pesanans.index') }}"
            class="flex items-center p-2 rounded {{ request()->routeIs('admin.pesanans.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-green-100' }}">
            <i class="fas fa-shopping-cart w-5"></i><span class="ml-3">Pesanan</span>
        </a> --}}

        <a href="{{ route('admin.promos.index') }}"
            class="flex items-center p-2 rounded {{ request()->routeIs('admin.promo.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-green-100' }}">
            <i class="fas fa-tags w-5"></i><span class="ml-3">Promo</span>
        </a>

        <a href="#" class="flex items-center p-2 rounded hover:bg-green-100">
            <i class="fas fa-newspaper w-5"></i><span class="ml-3">Artikel</span>
        </a>
        <a href="#" class="flex items-center p-2 rounded hover:bg-green-100">
            <i class="fas fa-comment-dots w-5"></i><span class="ml-3">Testimoni</span>
        </a>
        <a href="#" class="flex items-center p-2 rounded hover:bg-green-100">
            <i class="fas fa-user-friends w-5"></i><span class="ml-3">Daftar Pelanggan</span>
        </a>
        <a href="#" class="flex items-center p-2 rounded hover:bg-green-100">
            <i class="fas fa-user-shield w-5"></i><span class="ml-3">Role Pengguna</span>
        </a>
        <!-- Tambahkan menu lainnya -->
    </nav>
</aside>
