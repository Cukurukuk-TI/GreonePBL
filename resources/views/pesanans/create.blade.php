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
                        <!-- Pilihan alamat yang sudah ada -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alamat:</label>
                            <div class="space-y-2">
                                @foreach($alamats as $alamat)
                                    <div class="flex items-start space-x-2">
                                        <input type="radio" name="alamat_id" value="{{ $alamat->id }}" 
                                            id="alamat_{{ $alamat->id }}" 
                                            class="mt-1" 
                                            {{ old('alamat_id') == $alamat->id ? 'checked' : '' }}>
                                        <label for="alamat_{{ $alamat->id }}" class="flex-1 text-sm cursor-pointer">
                                            <div class="font-medium">{{ $alamat->label }} - {{ $alamat->nama_penerima }}</div>
                                            <div class="text-gray-600">{{ $alamat->detail_alamat }}, {{ $alamat->kota }}, {{ $alamat->provinsi }}</div>
                                            <div class="text-gray-500">{{ $alamat->nomor_hp }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Opsi alamat custom -->
                        {{-- <div class="border-t pt-3">
                            <div class="flex items-start space-x-2">
                                <input type="radio" name="alamat_id" value="" 
                                    id="alamat_custom" 
                                    class="mt-1" 
                                    {{ old('alamat_id') == '' ? 'checked' : '' }}>
                                <label for="alamat_custom" class="text-sm font-medium cursor-pointer">Alamat Lain</label>
                            </div>
                            
                        </div> --}}
                    @else
                        <!-- Jika belum ada alamat -->
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">Belum ada alamat yang ditambahkan</p>
                            <a href="{{ route('alamat.create') }}" 
                                class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                + Tambah Alamat
                            </a>
                        </div>
                        
                        <!-- Input alamat manual jika belum ada alamat -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Atau masukkan alamat manual:</label>
                            <textarea name="alamat_pengiriman_custom" rows="3" 
                                placeholder="Masukkan alamat lengkap pengiriman..."
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('alamat_pengiriman_custom') border-red-500 @enderror">{{ old('alamat_pengiriman_custom') }}</textarea>
                            @error('alamat_pengiriman_custom')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    
                    @if($alamats->count() > 0)
                        <a href="{{ route('alamat.index') }}" class="text-green-600 text-sm mt-2 inline-block">+ Tambah Alamat Baru</a>
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
                                <input type="number" name="jumlah" value="{{ old('jumlah', $defaultJumlah) }}" 
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
                            <span id="subtotal">Rp{{ number_format($produk->harga_produk * $defaultJumlah, 0, ',', '.') }}</span>
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
                            <span id="grand-total">Rp{{ number_format(($produk->harga_produk * $defaultJumlah) + 10000, 0, ',', '.') }}</span>
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
    const alamatRadios = document.querySelectorAll('input[name="alamat_id"]');
    const alamatCustomInput = document.getElementById('alamat_custom_input');
    const hargaSatuan = {{ $produk->harga_produk }};
    const ongkir = 10000;
    
    // Hitung harga awal berdasarkan default jumlah
    updateHarga();

    // Handle alamat custom input
    if (alamatCustomInput) {
        alamatRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '') {
                    alamatCustomInput.focus();
                }
            });
        });

        alamatCustomInput.addEventListener('focus', function() {
            const customRadio = document.getElementById('alamat_custom');
            if (customRadio) {
                customRadio.checked = true;
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
    promoSelect.addEventListener('change', updateHarga);
});
</script>
@endsection