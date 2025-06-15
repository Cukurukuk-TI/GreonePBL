@extends('layouts.pesanan')

@section('title', 'Buat Pesanan')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Buat Pesanan</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pesanans.store') }}" method="POST">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <!-- Alamat Pengiriman -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold mb-2">Alamat Pengiriman</h3>
                    
                    @if($alamats->count() > 0)
                        <!-- Dropdown pilihan alamat -->
                        <select id="alamat-select" class="w-full border border-gray-300 rounded px-3 py-2 mb-3 focus:outline-none focus:border-blue-400">
                            <option value="">-- Pilih Alamat --</option>
                            @foreach($alamats as $alamat)
                                <option value="{{ $alamat->detail_alamat }}, {{ $alamat->kota }}, {{ $alamat->provinsi }}" 
                                    data-label="{{ $alamat->label }}" 
                                    data-nama="{{ $alamat->nama_penerima }}">
                                    {{ $alamat->label }} - {{ $alamat->nama_penerima }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    
                    <textarea name="alamat_pengiriman" id="alamat-textarea" rows="3" 
                        placeholder="@if($alamats->count() > 0)Pilih alamat dari dropdown...@else Belum ada alamat yang ditambahkan. Masukkan alamat lengkap pengiriman...@endif"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('alamat_pengiriman') border-red-500 @enderror" 
                        @if($alamats->count() > 0) readonly @endif>{{ old('alamat_pengiriman') }}</textarea>
                    @error('alamat_pengiriman')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    
                    @if($alamats->count() > 0)
                        <a href="{{ route('alamat.index') }}" class="text-green-600 text-sm mt-2 inline-block">Kelola Alamat</a>
                    @else
                        <a href="{{ route('alamat.create') }}" class="text-green-600 text-sm mt-2 inline-block">+ Tambah Alamat Baru</a>
                    @endif
                </div>

                <!-- Metode Pengiriman -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold mb-2">Metode Pengiriman</h3>
                    <p class="text-gray-600">SiCepat Ultimate</p>
                    <button type="button" class="text-green-600 text-sm mt-2">Ubah Metode Pengiriman</button>
                </div>

                <!-- Promo -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold mb-2">Promo</h3>
                    @if($promos->count() > 0)
                        <select name="promo_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400">
                            <option value="">-- Pilih Promo --</option>
                            @foreach($promos as $promo)
                                <option value="{{ $promo->id }}" 
                                    data-discount="{{ $promo->besaran_potongan }}"
                                    data-minimum="{{ $promo->minimum_belanja }}">
                                    {{ $promo->nama_promo }} - {{ $promo->besaran_potongan }}% (Min. Rp{{ number_format($promo->minimum_belanja, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Pilih promo untuk mendapatkan diskon</p>
                    @else
                        <p class="text-gray-500">Tidak ada promo yang tersedia</p>
                    @endif
                    {{-- <button type="button" class="text-green-600 text-sm mt-2">Ubah Promo</button> --}}
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold mb-2">Metode Pembayaran</h3>
                    <p class="text-gray-600">BNI Virtual Account</p>
                    <button type="button" class="text-green-600 text-sm mt-2">Ubah Metode Pembayaran</button>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div>
                <!-- Detail Produk -->
                <div class="bg-white p-4 rounded-lg border mb-4">
                    <div class="flex items-center space-x-4">
                        @if($produk->gambar_produk)
                            <img src="{{ asset('storage/' . $produk->gambar_produk) }}" 
                                class="w-20 h-20 object-cover rounded">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $produk->nama_produk }}</h3>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="text-lg font-semibold">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</span>
                                <span class="text-gray-500">x</span>
                                <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" 
                                    min="1" max="{{ $produk->stok_produk }}"
                                    class="w-16 border border-gray-300 rounded px-2 py-1 text-center @error('jumlah') border-red-500 @enderror"
                                    id="jumlah-input">
                            </div>
                            @error('jumlah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Harga -->
                <div class="bg-white p-4 rounded-lg border">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Ongkos Kirim</span>
                            <span>Rp10.000</span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Diskon</span>
                            <span id="diskon">Rp0</span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Grand Total</span>
                            <span id="grand-total">Rp{{ number_format($produk->harga_produk + 10000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded mt-4">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput = document.getElementById('jumlah-input');
    const promoSelect = document.querySelector('select[name="promo_id"]');
    const alamatSelect = document.getElementById('alamat-select');
    const alamatTextarea = document.getElementById('alamat-textarea');
    const hargaSatuan = {{ $produk->harga_produk }};
    const ongkir = 10000;

    // Handle alamat selection
    if (alamatSelect) {
        alamatSelect.addEventListener('change', function() {
            if (this.value !== '') {
                alamatTextarea.value = this.value;
            } else {
                alamatTextarea.value = '';
            }
        });
    }

    function updateHarga() {
        const jumlah = parseInt(jumlahInput.value) || 1;
        const subtotal = hargaSatuan * jumlah;
        
        // Hitung diskon
        let diskon = 0;
        if (promoSelect.value) {
            const selectedOption = promoSelect.options[promoSelect.selectedIndex];
            const discountPercent = parseInt(selectedOption.dataset.discount) || 0;
            const minimum = parseInt(selectedOption.dataset.minimum) || 0;
            
            if (subtotal >= minimum) {
                diskon = (subtotal * discountPercent) / 100;
            }
        }
        
        const grandTotal = subtotal - diskon + ongkir;
        
        // Update tampilan
        document.getElementById('subtotal').textContent = 'Rp' + subtotal.toLocaleString('id-ID');
        document.getElementById('diskon').textContent = 'Rp' + diskon.toLocaleString('id-ID');
        document.getElementById('grand-total').textContent = 'Rp' + grandTotal.toLocaleString('id-ID');
    }

    jumlahInput.addEventListener('input', updateHarga);
    if (promoSelect) {
        promoSelect.addEventListener('change', updateHarga);
    }
});
</script>
@endsection