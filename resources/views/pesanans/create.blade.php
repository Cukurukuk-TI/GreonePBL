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

    <!-- Alert untuk stok habis -->
    @if($produk->stok_produk <= 0)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Maaf!</strong> Produk ini sedang kehabisan stok dan tidak dapat dipesan.
        </div>
    @endif

    <form action="{{ route('pesanans.store') }}" method="POST" id="form-pesanan">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <!-- Alamat Pengiriman -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold mb-2">Alamat Pengiriman</h3>
                    
                    @if($alamats->count() > 0)
                        <!-- Dropdown alamat -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alamat:</label>
                            <select name="alamat_id" 
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('alamat_id') border-red-500 @enderror {{ $produk->stok_produk <= 0 ? 'bg-gray-100' : '' }}" 
                                id="alamat-select" {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>
                                <option value="">-- Pilih Alamat --</option>
                                @foreach($alamats as $alamat)
                                    <option value="{{ $alamat->id }}" 
                                        {{ old('alamat_id') == $alamat->id ? 'selected' : '' }}
                                        data-label="{{ $alamat->label }}"
                                        data-nama="{{ $alamat->nama_penerima }}"
                                        data-detail="{{ $alamat->detail_alamat }}"
                                        data-kota="{{ $alamat->kota }}"
                                        data-provinsi="{{ $alamat->provinsi }}"
                                        data-hp="{{ $alamat->nomor_hp }}">
                                        {{ $alamat->label }} - {{ $alamat->nama_penerima }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Detail alamat yang dipilih -->
                        <div id="alamat-detail" class="bg-gray-50 p-3 rounded border" style="display: none;">
                            <div class="text-sm">
                                <div class="font-medium" id="detail-nama"></div>
                                <div class="text-gray-600" id="detail-alamat"></div>
                                <div class="text-gray-500" id="detail-hp"></div>
                            </div>
                        </div>
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
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('alamat_pengiriman_custom') border-red-500 @enderror {{ $produk->stok_produk <= 0 ? 'bg-gray-100' : '' }}"
                                {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>{{ old('alamat_pengiriman_custom') }}</textarea>
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
                    <button type="button" class="text-green-600 text-sm mt-2" {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>Ubah Metode Pengiriman</button>
                </div>

                <!-- Promo -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold mb-2">Promo</h3>
                    @if($promos->count() > 0)
                        <select name="promo_id" 
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 {{ $produk->stok_produk <= 0 ? 'bg-gray-100' : '' }}"
                            {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>
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
                    <button type="button" class="text-green-600 text-sm mt-2" {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>Ubah Metode Pembayaran</button>
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
                            
                            <!-- Status Stok -->
                            <div class="mt-1">
                                @if($produk->stok_produk <= 0)
                                    <span class="text-red-500 text-sm font-medium">Stok Habis</span>
                                @elseif($produk->stok_produk <= 5)
                                    <span class="text-orange-500 text-sm font-medium">Stok Terbatas ({{ $produk->stok_produk }} tersisa)</span>
                                @else
                                    <span class="text-green-500 text-sm font-medium">Stok: {{ $produk->stok_produk }}</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="text-lg font-semibold">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</span>
                                <span class="text-gray-500">x</span>
                                <input type="number" name="jumlah" value="{{ old('jumlah', $defaultJumlah) }}" 
                                    min="1" max="{{ $produk->stok_produk }}"
                                    class="w-16 border border-gray-300 rounded px-2 py-1 text-center @error('jumlah') border-red-500 @enderror {{ $produk->stok_produk <= 0 ? 'bg-gray-100' : '' }}"
                                    id="jumlah-input" {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>
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

                    @if($produk->stok_produk <= 0)
                        <button type="button" disabled
                            class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded mt-4 cursor-not-allowed">
                            Stok Habis - Tidak Dapat Dipesan
                        </button>
                    @else
                        <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded mt-4">
                            Bayar Sekarang
                        </button>
                    @endif
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
    const alamatDetail = document.getElementById('alamat-detail');
    const alamatCustomInput = document.getElementById('alamat-custom-input');
    const formPesanan = document.getElementById('form-pesanan');
    const hargaSatuan = {{ $produk->harga_produk }};
    const stokTersedia = {{ $produk->stok_produk }};
    const ongkir = 10000;
    
    // Cek stok saat halaman dimuat
    if (stokTersedia <= 0) {
        showStockAlert('Produk ini sedang kehabisan stok dan tidak dapat dipesan.');
        return;
    }
    
    // Hitung harga awal berdasarkan default jumlah
    updateHarga();

    // Handle alamat dropdown change
    if (alamatSelect) {
        alamatSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            if (selectedValue === 'custom') {
                // Tampilkan input alamat custom
                alamatDetail.style.display = 'none';
                if (alamatCustomInput) alamatCustomInput.style.display = 'block';
            } else if (selectedValue !== '') {
                // Tampilkan detail alamat yang dipilih
                const selectedOption = this.options[this.selectedIndex];
                const nama = selectedOption.dataset.nama;
                const detail = selectedOption.dataset.detail;
                const kota = selectedOption.dataset.kota;
                const provinsi = selectedOption.dataset.provinsi;
                const hp = selectedOption.dataset.hp;
                
                document.getElementById('detail-nama').textContent = nama;
                document.getElementById('detail-alamat').textContent = `${detail}, ${kota}, ${provinsi}`;
                document.getElementById('detail-hp').textContent = hp;
                
                alamatDetail.style.display = 'block';
                if (alamatCustomInput) alamatCustomInput.style.display = 'none';
            } else {
                // Sembunyikan semua detail
                alamatDetail.style.display = 'none';
                if (alamatCustomInput) alamatCustomInput.style.display = 'none';
            }
        });
        
        // Trigger change event jika ada value yang sudah dipilih sebelumnya
        if (alamatSelect.value) {
            alamatSelect.dispatchEvent(new Event('change'));
        }
    }

    // Validasi input jumlah
    if (jumlahInput) {
        jumlahInput.addEventListener('input', function() {
            const jumlah = parseInt(this.value) || 1;
            
            // Validasi batas minimum dan maksimum
            if (jumlah < 1) {
                this.value = 1;
                showStockAlert('Jumlah minimal pembelian adalah 1.');
                return;
            }
            
            if (jumlah > stokTersedia) {
                this.value = stokTersedia;
                showStockAlert(`Jumlah maksimal yang dapat dipesan adalah ${stokTersedia} sesuai stok yang tersedia.`);
                return;
            }
            
            updateHarga();
        });

        // Validasi saat form disubmit
        formPesanan.addEventListener('submit', function(e) {
            const jumlah = parseInt(jumlahInput.value) || 1;
            
            if (stokTersedia <= 0) {
                e.preventDefault();
                showStockAlert('Produk ini sedang kehabisan stok dan tidak dapat dipesan.');
                return;
            }
            
            if (jumlah > stokTersedia) {
                e.preventDefault();
                showStockAlert(`Jumlah yang dipesan (${jumlah}) melebihi stok yang tersedia (${stokTersedia}).`);
                jumlahInput.value = stokTersedia;
                updateHarga();
                return;
            }
            
            if (jumlah < 1) {
                e.preventDefault();
                showStockAlert('Jumlah minimal pembelian adalah 1.');
                jumlahInput.value = 1;
                updateHarga();
                return;
            }
        });
    }

    function updateHarga() {
        const jumlah = parseInt(jumlahInput.value) || 1;
        const subtotal = hargaSatuan * jumlah;
        
        // Hitung diskon
        let diskon = 0;
        if (promoSelect && promoSelect.value) {
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

    function showStockAlert(message) {
        // Hapus alert sebelumnya jika ada
        const existingAlert = document.querySelector('.stock-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Buat alert baru
        const alertDiv = document.createElement('div');
        alertDiv.className = 'stock-alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        alertDiv.innerHTML = `<strong>Peringatan!</strong> ${message}`;
        
        // Masukkan alert di bagian atas form
        const form = document.getElementById('form-pesanan');
        form.parentNode.insertBefore(alertDiv, form);
        
        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    if (promoSelect) {
        promoSelect.addEventListener('change', updateHarga);
    }
});
</script>
@endsection