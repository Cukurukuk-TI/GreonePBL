@extends('layouts.appnoslider') {{-- Menggunakan layout utama agar header & footer konsisten --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Kolom Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                {{-- Info Pengguna --}}
                <div class="flex flex-col items-center text-center mb-6 pb-6 border-b">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover mb-4 border-4 border-green-200">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4 text-3xl text-gray-500">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <h3 class="font-bold text-lg text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                </div>

                {{-- Menu Navigasi Sidebar --}}
                <nav class="space-y-2">
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('profile.show') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                        <i class="fas fa-user-circle fa-fw w-5"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                        <i class="fas fa-user-edit fa-fw w-5"></i>
                        <span>Edit Profil</span>
                    </a>
                    <a href="{{ route('password.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('password.edit') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                        <i class="fas fa-key fa-fw w-5"></i>
                        <span>Ganti Kata Sandi</span>
                    </a>
                    <a href="{{ route('alamat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('alamat.index') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                        <i class="fas fa-map-marker-alt fa-fw w-5"></i>
                        <span>Alamat Pengiriman</span>
                    </a>
                    <a href="{{ route('user.pesanan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('user.pesanan') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                        <i class="fas fa-receipt fa-fw w-5"></i>
                        <span>Pesanan Saya</span>
                    </a>

                    {{-- Garis pemisah --}}
                    <div class="border-t my-2"></div>

                    {{-- Tombol Logout --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors text-red-500 hover:bg-red-50 hover:text-red-700">
                        <i class="fas fa-sign-out-alt fa-fw w-5"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
        </aside>

        {{-- Kolom Konten Utama --}}
        <main class="lg:col-span-3">
            <div class="bg-white p-8 rounded-xl shadow-lg min-h-full">
                @yield('profile-content')
            </div>
        </main>

    </div>
</div>
@endsection
