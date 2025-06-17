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

    {{-- content --}}
    <div class="bg-white p-8 max-w-xl mx-auto mt-6">
        @yield('profile-content')
    </div>
    {{-- Footer --}}
    @include('adminlayouts.footer')

</body>

<script>
    let idleTime = 0;

    // Tambah waktu idle setiap 1 detik
    const idleInterval = setInterval(timerIncrement, 1000);

    // Reset jika ada aktivitas
    ['mousemove', 'keydown', 'scroll', 'click'].forEach(evt => {
        document.addEventListener(evt, resetIdleTime, false);
    });

    function resetIdleTime() {
        idleTime = 0;
    }

    function timerIncrement() {
        idleTime++;
        if (idleTime >= 60) { // 60 detik
            clearInterval(idleInterval);
            window.location.href = "{{ route('logout') }}";
        }
    }
</script>

</html>
