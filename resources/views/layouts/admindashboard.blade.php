<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin | Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

    {{-- Header --}}
    @include('adminlayouts.header')

    {{-- Body --}}
    <div class="flex min-h-screen ">
        {{-- Sidebar --}}
        @include('adminlayouts.sidebar')

        {{-- Content --}}
        <main class="flex-1 p-6 ">
            @yield('content')
        </main>
    </div>

    {{-- Footer --}}
    @include('adminlayouts.footer')

</body>
</html>
