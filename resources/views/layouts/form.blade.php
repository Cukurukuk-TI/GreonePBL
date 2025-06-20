<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Konfigurasi Kustom Tailwind & Style Dasar --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    colors: {
                        'brand-green': '#005E25',
                        'brand-green-dark': '#004d1f',
                        'brand-text': '#374151',
                        'brand-text-muted': '#6B7280',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'poppins', sans-serif; }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <img src="{{ asset('img/bekgron.jpg') }}" class="w-full h-full object-cover blur-sm">
        </div>

        <main class="relative z-10 flex items-center justify-center min-h-screen p-4">
            {{-- KUNCI PERUBAHAN: Menggunakan @yield('content') sebagai placeholder --}}
            @yield('content')
        </main>
    </div>
</body>
</html>