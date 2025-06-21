<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>503 - Sedang dalam Perawatan</title>
    {{-- Bagian head lainnya sama dengan 404.blade.php --}}
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
        <div class="max-w-lg w-full">
            <img src="{{asset('img/503.gif')}}" alt="">
            <h2 class="mt-6 text-3xl font-bold text-gray-800">Situs Sedang dalam Perawatan</h2>
            <p class="mt-2 text-md text-gray-600">Kami sedang melakukan beberapa pembaruan untuk meningkatkan pengalaman Anda. Situs akan segera kembali normal. Terima kasih atas kesabaran Anda.</p>
        </div>
    </div>
</body>
</html>