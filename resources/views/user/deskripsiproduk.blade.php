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
                        <input id="jumlah" type="number" name="jumlah" value="1" min="1" max="{{ $produk->stok_produk }}"
                            class="w-12 text-center appearance-none border-none bg-transparent focus:outline-none focus:ring-0" />
                        <button type="button" class="text-lg font-bold px-2" onclick="tambahJumlah()">+</button>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 flex gap-4">
                    <button onclick="beliSekarang()" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded">
                        Beli Sekarang
                    </button>
                    <form method="POST" action="{{ route('keranjang.store') }}" class="inline">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <input type="hidden" name="jumlah" id="jumlah-keranjang" value="1">
                        <button type="submit"
                            class="border border-green-500 text-green-500 hover:bg-green-100 font-bold py-2 px-4 rounded shadow">
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script jumlah dan penyesuaian tinggi deskripsi -->
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
        function beliSekarang() {
            const jumlah = document.getElementById('jumlah').value;
            const produkId = {{ $produk->id }};
            
            // Redirect ke halaman create pesanan dengan parameter jumlah
            window.location.href = "{{ route('pesanans.create', $produk->id) }}" + "?jumlah=" + jumlah;
        }
    </script>
@endsection