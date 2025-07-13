<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Bgd Hydrofarm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js untuk animasi --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                    },
                    transitionProperty: {
                        'all': 'all',
                        'transform': 'transform'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'poppins', sans-serif; }
        
        /* Animasi tambahan untuk smoothness */
        .form-animate {
            animation: slideUp 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }
        
        @keyframes slideUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="antialiased" x-data>
    <div class="relative min-h-screen">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <img src="{{ asset('img/bekgron.jpg') }}" class="w-full h-full object-cover blur-sm">
        </div>

        <main class="relative z-10 flex items-center justify-center min-h-screen p-4">
            {{-- Konten dengan animasi muncul dari bawah --}}
            <div 
                x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 50)"
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                {{-- class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg form-animate" --}}
            >
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>