@extends('layouts.appnoslider')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-8 text-left">Detail Produk</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
        {{-- Gambar --}}
        <div class="bg-gray-200 rounded-xl aspect-square flex items-center justify-center overflow-hidden">
            <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                 alt="{{ $produk->nama_produk }}"
                 class="w-full h-full object-cover">
        </div>

        {{-- Detail Produk --}}
        <div class="text-left">
            <h2 class="text-2xl font-extrabold text-gray-800 mb-1">{{ $produk->nama_produk }}</h2>
            <p class="text-sm font-semibold  text-gray-700 mb-3">Kategori : {{ $produk->kategori->nama_kategori }}</p>
            {{-- stok produk --}}
            <p class="text-sm font-semibold text-gray-700 mb-3">Stok : {{$produk->stok_produk}} </p>
            <p class="text-3xl text-orange-500 font-extrabold mb-6">
                Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
            </p>

            <div id="deskripsi-container" class="text-gray-500 leading-relaxed mb-6 relative">
                <p id="deskripsi-teks" class="overflow-hidden whitespace-pre-wrap"></p>
                <button id="toggle-deskripsi" class="mt-2 text-green-600 hover:underline text-sm"></button>
            </div>

            <div class="flex items-center gap-4 mb-6">
                <label class="font-bold text-lg">Jumlah:</label>
                <div class="flex items-center border rounded-lg px-3 py-1">
                    <button onclick="kurangiJumlah()" type="button" class="text-xl font-bold px-2">−</button>
                    <input id="jumlah" name="jumlah" type="number"
                           value="1" min="1" max="{{ $produk->stok_produk }}"
                           class="w-12 text-center bg-transparent border-none focus:outline-none">
                    <button onclick="tambahJumlah()" type="button" class="text-xl font-bold px-2">+</button>
                </div>
            </div>

            {{-- tombolodon --}}
            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <form method="POST" action="{{ route('keranjang.store') }}" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <input type="hidden" name="jumlah" id="jumlah-keranjang" value="1">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 border hover:border-green-800 text-green-600 font-semibold rounded-xl hover:bg-green-50 transition flex items-center justify-center gap-2">
                        <i class="fas fa-cart-plus"></i>
                        <span>Tambah ke Keranjang</span>
                    </button>
                </form>

                <button onclick="beliSekarang()"
                    class="w-full sm:w-auto px-6 py-3 hover:bg-green-800 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-50 transition flex items-center justify-center gap-2">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Beli Sekarang</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const fullText = `{{ $produk->deskripsi_produk }}`;
    const previewLimit = 250;
    const deskripsiTeks = document.getElementById('deskripsi-teks');
    const toggleBtn = document.getElementById('toggle-deskripsi');

    let expanded = false;

    function updateDeskripsi() {
        if (fullText.length <= previewLimit) {
            deskripsiTeks.textContent = fullText;
            toggleBtn.style.display = 'none';
        } else {
            deskripsiTeks.textContent = expanded ? fullText : fullText.slice(0, previewLimit) + '...';
            toggleBtn.textContent = expanded ? 'Sembunyikan' : 'Read more...';
            toggleBtn.style.display = 'inline';
        }
    }

    toggleBtn.addEventListener('click', function () {
        expanded = !expanded;
        updateDeskripsi();
    });

    updateDeskripsi();

    function tambahJumlah() {
        const input = document.getElementById('jumlah');
        const hidden = document.getElementById('jumlah-keranjang');
        let val = parseInt(input.value || 1);
        if (val < {{ $produk->stok_produk }}) {
            input.value = ++val;
            hidden.value = val;
        }
    }

    function kurangiJumlah() {
        const input = document.getElementById('jumlah');
        const hidden = document.getElementById('jumlah-keranjang');
        let val = parseInt(input.value || 1);
        if (val > 1) {
            input.value = --val;
            hidden.value = val;
        }
    }

    document.getElementById('jumlah').addEventListener('input', function () {
        let val = parseInt(this.value) || 1;
        if (val < 1) val = 1;
        if (val > {{ $produk->stok_produk }}) val = {{ $produk->stok_produk }};
        this.value = val;
        document.getElementById('jumlah-keranjang').value = val;
    });

    function beliSekarang() {
        const jumlah = document.getElementById('jumlah').value;
        window.location.href = "{{ route('pesanans.create', $produk->id) }}" + "?jumlah=" + jumlah;
    }
</script>
@if(session('berhasil_keranjang'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Produk berhasil ditambahkan ke keranjang.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Lihat Keranjang',
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('keranjang.index') }}";
            }
        });
    });
</script>
@endif

@endsection
