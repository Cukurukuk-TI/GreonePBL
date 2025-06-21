@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="bg-white">

    {{-- Hero Section --}}
    <section class="text-center py-16 sm:py-20 px-4">
        <h1 class="text-base font-semibold text-green-600">Tentang Kami</h1>
        <p class="mt-2 text-4xl sm:text-5xl font-extrabold text-gray-800">
            Membawa Kesegaran Hidroponik ke Meja Anda
        </p>
        <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600">
            Kami percaya bahwa makanan sehat dan segar adalah hak semua orang. Pelajari lebih lanjut tentang perjalanan dan misi kami di Bgd Hydrofarm.
        </p>
    </section>

    {{-- Visi & Solusi Section --}}
    <section class="bg-gray-50 py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                {{-- Visi --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-full">
                        <i class="fas fa-rocket fa-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Bagaimana Kami Memulai</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Bgd Hydrofarm memulai perjalanannya dengan visi untuk menghadirkan sistem pertanian hidroponik yang efisien dan ramah lingkungan. Berawal dari kebun kecil, kami bertekad menjawab tantangan pangan masa kini dengan pendekatan berkelanjutan.
                    </p>
                </div>

                {{-- Solusi --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-full">
                        <i class="fas fa-lightbulb fa-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Solusi yang Kami Tawarkan</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Kami mengembangkan sistem hidroponik terintegrasi yang menghasilkan sayuran berkualitas tinggi tanpa pestisida, serta menghemat air hingga 90%. Solusi kami cocok untuk skala rumah tangga maupun komersial.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- Tim Kami Section --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">Bertemu dengan Tim Kami</h2>
                <p class="mt-4 text-lg text-gray-600">Orang-orang hebat di balik kesegaran produk kami.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Tim 1 --}}
                <div class="text-center">
                    <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md transition hover:scale-105" src="{{asset('img/obat anti stres.jpeg')}}" alt="Foto Alex Wijaya">
                    <h3 class="mt-4 text-base font-semibold text-gray-800">Jiwoo</h3>
                    <p class="text-sm text-green-600 font-medium">Founder & CEO</p>
                </div>

                {{-- Tim 2 --}}
                <div class="text-center">
                    <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md transition hover:scale-105" src="{{asset('img/kebun.png')}}" alt="Foto Dita Rahma">
                    <h3 class="mt-4 text-base font-semibold text-gray-800">Tukang Kebun</h3>
                    <p class="text-sm text-green-600 font-medium">Head of Operations</p>
                </div>

                {{-- Tim 3 --}}
                <div class="text-center">
                    <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md transition hover:scale-105" src="{{asset('img/malive.jpeg')}}" alt="Foto Rino Mahendra">
                    <h3 class="mt-4 text-base font-semibold text-gray-800">Carmen</h3>
                    <p class="text-sm text-green-600 font-medium">Lead Agronomist</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
