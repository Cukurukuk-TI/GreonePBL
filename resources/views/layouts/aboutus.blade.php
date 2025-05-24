<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ config('app.name', 'Bgd Hydrofarm') }} - @yield('title')</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Splide Carousel CSS (optional) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css" />

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('css/aboutus.css') }}">
  @stack('styles')

  <!-- Optional inline styles -->
  @hasSection('inline-style')
    <style>
      @yield('inline-style')
    </style>

  @endif
</head>
<body class="flex flex-col min-h-screen bg-gray-50">

  {{-- Header --}}
  @include('partials.header')

  {{-- Carousel jika dibutuhkan --}}
  @hasSection('carousel')
    @yield('carousel')
  @endif

  <!-- Main Content -->
  <main class="flex-grow pt-2 pb-2 px-6">
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  <!-- Splide Carousel JS (optional) -->
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>

  <!-- Alpine.js -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Scripts -->
  @yield('scripts')
  @stack('scripts')
</body>
</html>
