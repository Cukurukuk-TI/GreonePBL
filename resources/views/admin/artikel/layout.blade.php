@extends('layouts.admindashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Konten</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- Navigasi Tab -->
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.artikel.index') ? 'active' : '' }}" href="{{ route('admin.artikel.index') }}">
                        Daftar Artikel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.artikel.kategori.index') ? 'active' : '' }}" href="{{ route('admin.artikel.kategori.index') }}">
                        Kategori Artikel
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @yield('artikel_content')
        </div>
    </div>
</div>
@endsection
