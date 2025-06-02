{{-- layouts/alamat.blade.php --}}
@extends('layouts.appnoslider')

@section('content')
<div class="flex flex-col md:flex-row gap-4 p-4">

    {{-- Sidebar --}}
    <aside class="w-full md:w-1/4">
        @include('profile.sidebar')
    </aside>

    {{-- Konten --}}
    <main class="w-full md:w-3/4 px-0 md:px-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Alamat</h1>
            <p class="text-sm text-gray-500">Kelola alamat pengiriman kamu di sini.</p>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('alamat-content')
    </main>

</div>
@endsection
