<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Halaman Tidak Ditemukan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    colors: { 'brand-green': '#005E25' }
                }
            }
        }
    </script>
    <style> body { font-family: 'poppins', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center text-center px-4">
        <h2 class="mt-6 text-3xl font-bold text-gray-800">Halaman Tidak Ditemukan...</h2>
        <img src="{{asset('img/404.gif')}}" alt="">
        <div class="max-w-md w-full">
            <div class="mt-8">
                <a href="{{ url('/') }}" class="inline-block bg-blue-500 hover:bg-opacity-90 text-white font-regular px-6 py-3 rounded-lg shadow-md transition-transform hover:scale-105">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>