@extends('layouts.appnoslider')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start test">
            <div id="gambar-produk" class="relative">
                <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}"
                    class="rounded-2xl border-4 border-blue-200 w-full object-cover max-h-[400px]" id="img-produk">
            </div>

            <div>
                <span class="inline-block px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full mb-2">
                    {{ $produk->kategori->nama_kategori }}
                </span>

                <h3 class="text-2xl font-bold">{{ $produk->nama_produk }}</h3>

                <p class="text-orange-500 text-2xl font-bold mt-2">
                    Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                </p>

                <div id="deskripsi-produk"
                    style="max-height: 200px; overflow-y: auto; margin-top: 1rem; padding-right: 0.5rem; border: 1px solid #ffffff;
                    border-radius: 0.5rem;">
                    {{-- Hapus text-center dari sini --}}
                    <p class="text-gray-600 leading-relaxed whitespace-normal m-0 p-0">
                        {{ $produk->deskripsi_produk }}
                    </p>
                </div>

                <p class="text-sm text-gray-500 mt-2">Stok: <span class="font-semibold">{{ $produk->stok_produk }}</span>
                </p>

                <div class="mt-6 flex items-center gap-4">
                    <label class="font-semibold text-lg">Jumlah:</label>
                    <div class="flex items-center border rounded px-2 py-1 gap-2">
                        <button type="button" class="text-lg font-bold px-2" onclick="kurangiJumlah()">−</button>
                        <input id="jumlah" type="number" name="jumlah" value="1" min="1" max="{{ $produk->stok_produk }}"
                            class="w-12 text-center appearance-none border-none bg-transparent focus:outline-none focus:ring-0" />
                        <button type="button" class="text-lg font-bold px-2" onclick="tambahJumlah()">+</button>
                    </div>
                </div>
                <div class="mt-6">
                    <form method="POST" action="{{ route('keranjang.store') }}">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <input type="hidden" name="jumlah" id="jumlah-keranjang" value="1">
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <hr class="my-8">

        <h3 class="text-xl font-bold mb-4">Testimoni Pelanggan</h3>

        @if($produk->testimonis->isEmpty())
            <p class="text-gray-500 text-center py-8">Belum ada testimoni untuk produk ini.</p>
        @else
            <div class="space-y-6">
                @foreach($produk->testimonis as $testimoni)
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center mb-2">
                            @if($testimoni->user->foto_profil)
                                <img src="{{ asset('storage/' . $testimoni->user->foto_profil) }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 mr-3">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            {{-- TAMPILKAN NAMA USER DI SINI --}}
                            <div class="font-semibold text-gray-800">{{ $testimoni->user->name }}</div>
                        </div>

                        <div class="flex items-center mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $testimoni->rating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ $testimoni->rating }} / 5</span>
                        </div>

                        {{-- Hapus text-center atau tambahkan text-left di sini jika perlu --}}
                        <p class="text-gray-700 mb-3">{{ $testimoni->komentar }}</p>

                        @if($testimoni->foto_testimoni)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $testimoni->foto_testimoni) }}" alt="Foto Testimoni" class="max-w-xs h-auto rounded-lg shadow-md">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function tambahJumlah() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah-keranjang');
            const stokMaksimal = {{ $produk->stok_produk }};
            const currentValue = parseInt(input.value || 1);

            if (currentValue < stokMaksimal) {
                const newValue = currentValue + 1;
                input.value = newValue;
                hiddenInput.value = newValue;
            }
        }

        function kurangiJumlah() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah-keranjang');
            if (parseInt(input.value) > 1) {
                const newValue = parseInt(input.value) - 1;
                input.value = newValue;
                hiddenInput.value = newValue;
            }
        }

        // Sinkronisasi saat input berubah manual
        document.getElementById('jumlah').addEventListener('input', function() {
            const stokMaksimal = {{ $produk->stok_produk }};
            let value = parseInt(this.value) || 1;

            // Validasi batas minimum dan maksimum
            if (value < 1) value = 1;
            if (value > stokMaksimal) value = stokMaksimal;

            this.value = value;
            document.getElementById('jumlah-keranjang').value = value;
        });

    </script>
@endsection@extends('layouts.appnoslider')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start test">
            <div id="gambar-produk" class="relative">
                <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}"
                    class="rounded-2xl border-4 border-blue-200 w-full object-cover max-h-[400px]" id="img-produk">
            </div>

            <div>
                <span class="inline-block px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full mb-2">
                    {{ $produk->kategori->nama_kategori }}
                </span>

                <h3 class="text-2xl font-bold">{{ $produk->nama_produk }}</h3>

                <p class="text-orange-500 text-2xl font-bold mt-2">
                    Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                </p>

                <div id="deskripsi-produk"
                    style="max-height: 200px; overflow-y: auto; margin-top: 1rem; padding-right: 0.5rem; border: 1px solid #ffffff;
                    border-radius: 0.5rem;">
                    {{-- Hapus text-center dari sini --}}
                    <p class="text-gray-600 leading-relaxed whitespace-normal m-0 p-0">
                        {{ $produk->deskripsi_produk }}
                    </p>
                </div>

                <p class="text-sm text-gray-500 mt-2">Stok: <span class="font-semibold">{{ $produk->stok_produk }}</span>
                </p>

                <div class="mt-6 flex items-center gap-4">
                    <label class="font-semibold text-lg">Jumlah:</label>
                    <div class="flex items-center border rounded px-2 py-1 gap-2">
                        <button type="button" class="text-lg font-bold px-2" onclick="kurangiJumlah()">−</button>
                        <input id="jumlah" type="number" name="jumlah" value="1" min="1" max="{{ $produk->stok_produk }}"
                            class="w-12 text-center appearance-none border-none bg-transparent focus:outline-none focus:ring-0" />
                        <button type="button" class="text-lg font-bold px-2" onclick="tambahJumlah()">+</button>
                    </div>
                </div>
                    <div class="mt-6">
                        <form method="POST" action="{{ route('keranjang.store') }}">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <input type="hidden" name="jumlah" id="jumlah-keranjang" value="1">
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
            </div>
        </div>


        <hr class="my-8">

        <h3 class="text-xl font-bold mb-4">Testimoni Pelanggan</h3>

        @if($produk->testimonis->isEmpty())
            <p class="text-gray-500 text-center py-8">Belum ada testimoni untuk produk ini.</p>
        @else
            <div class="space-y-6">
                @foreach($produk->testimonis as $testimoni)
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center mb-2">
                            @if($testimoni->user->foto_profil)
                                <img src="{{ asset('storage/' . $testimoni->user->foto_profil) }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 mr-3">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            {{-- TAMPILKAN NAMA USER DI SINI --}}
                            <div class="font-semibold text-gray-800">{{ $testimoni->user->nama }}</div>
                        </div>

                        <div class="flex items-center mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $testimoni->rating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ $testimoni->rating }} / 5</span>
                        </div>

                        {{-- Hapus text-center atau tambahkan text-left di sini jika perlu --}}
                        <p class="text-gray-700 mb-3">{{ $testimoni->komentar }}</p>

                        @if($testimoni->foto_testimoni)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $testimoni->foto_testimoni) }}" alt="Foto Testimoni" class="max-w-xs h-auto rounded-lg shadow-md">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function tambahJumlah() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah-keranjang');
            const stokMaksimal = {{ $produk->stok_produk }};
            const currentValue = parseInt(input.value || 1);

            if (currentValue < stokMaksimal) {
                const newValue = currentValue + 1;
                input.value = newValue;
                hiddenInput.value = newValue;
            }
        }

        function kurangiJumlah() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah-keranjang');
            if (parseInt(input.value) > 1) {
                const newValue = parseInt(input.value) - 1;
                input.value = newValue;
                hiddenInput.value = newValue;
            }
        }

        // Sinkronisasi saat input berubah manual
        document.getElementById('jumlah').addEventListener('input', function() {
            const stokMaksimal = {{ $produk->stok_produk }};
            let value = parseInt(this.value) || 1;

            // Validasi batas minimum dan maksimum
            if (value < 1) value = 1;
            if (value > stokMaksimal) value = stokMaksimal;

            this.value = value;
            document.getElementById('jumlah-keranjang').value = value;
        });

        // Fungsi untuk beli sekarang dengan jumlah
    </script>
@endsection
