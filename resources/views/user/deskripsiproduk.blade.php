@extends('layouts.appnoslider')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start test">
            <!-- Gambar Produk -->
            <div id="gambar-produk" class="relative">
                <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}"
                    class="rounded-2xl border-4 border-blue-200 w-full object-cover max-h-[400px]" id="img-produk">
            </div>

            <!-- Informasi Produk -->
            <div>
                <!-- Kategori -->
                <span class="inline-block px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full mb-2">
                    {{ $produk->kategori->nama_kategori }}
                </span>

                <!-- Nama Produk -->
                <h3 class="text-2xl font-bold">{{ $produk->nama_produk }}</h3>

                <!-- Harga -->
                <p class="text-orange-500 text-2xl font-bold mt-2">
                    Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                </p>

                <!-- Kotak Deskripsi dengan scroll, tinggi disesuaikan otomatis -->
                <div id="deskripsi-produk"
                    style="max-height: 200px; overflow-y: auto; margin-top: 1rem; padding-right: 0.5rem; border: 1px solid #ffffff;
                     border-radius: 0.5rem;">
                    <p class="text-gray-600 text-center leading-relaxed whitespace-normal m-0 p-0">
                        {{ $produk->deskripsi_produk }}
                    </p>
                </div>

                <!-- Stok -->
                <p class="text-sm text-gray-500 mt-2">Stok: <span class="font-semibold">{{ $produk->stok_produk }}</span>
                </p>

                <!-- Jumlah dan Tombol -->
                <div class="mt-6 flex items-center gap-4">
                    <label class="font-semibold text-lg">Jumlah:</label>
                    <div class="flex items-center border rounded px-2 py-1 gap-2">
                        <button type="button" class="text-lg font-bold px-2" onclick="kurangiJumlah()">−</button>
                        <input id="jumlah" type="number" name="jumlah" value="1" min="1"
                            class="w-12 text-center appearance-none border-none bg-transparent focus:outline-none focus:ring-0" />
                        <button type="button" class="text-lg font-bold px-2" onclick="tambahJumlah()">+</button>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 flex gap-4">
                    <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded shadow">
                        Beli Sekarang
                    </button>
                    <button
                        class="border border-green-500 text-green-500 hover:bg-green-100 font-bold py-2 px-4 rounded shadow">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script jumlah dan penyesuaian tinggi deskripsi -->
    <script>
        function tambahJumlah() {
            const input = document.getElementById('jumlah');
            input.value = parseInt(input.value || 1) + 1;
        }

        function kurangiJumlah() {
            const input = document.getElementById('jumlah');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Fungsi untuk menyamakan tinggi div deskripsi dengan gambar
        // function setDeskripsiHeight() {
        //     const img = document.getElementById('img-produk');
        //     const deskripsi = document.getElementById('deskripsi-produk');

        //     if (img && deskripsi) {
        //         // Tunggu gambar selesai load untuk dapat tinggi sebenarnya
        //         if (img.complete) {
        //             deskripsi.style.maxHeight = img.clientHeight + 'px';
        //         } else {
        //             img.onload = () => {
        //                 deskripsi.style.maxHeight = img.clientHeight + 'px';
        //             };
        //         }
        //     }
        // }

        // // Jalankan saat load dan saat jendela diresize
        // window.addEventListener('load', setDeskripsiHeight);
        // window.addEventListener('resize', setDeskripsiHeight);
    </script>
@endsection
