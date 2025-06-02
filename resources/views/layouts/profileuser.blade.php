@extends('layouts.appnoslider')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-md">
        @include('profile.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        @yield('profile-content')
    </div>
</div>
@endsection