{{-- layouts/alamat.blade.php --}}
@extends('layouts.appnoslider')

@section('content')
<div class="w-full max-w-5xl mx-auto p-4 md:p-6">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Manajemen Alamat</h1>
        <p class="text-sm text-gray-500">Kelola alamat pengiriman kamu di sini.</p>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @yield('alamat-content')

</div>
@endsection
