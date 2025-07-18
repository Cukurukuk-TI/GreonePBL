@extends('layouts.appnoslider')

@section('title', 'Checkout')

@push('styles')
<style>
    .step-card.active {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    .option-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .step-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
    }
    .step-card {
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .step-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    #pay-button:disabled {
        background-color: #9ca3af;
        background-image: none;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Checkout</h1>
        <a href="{{ route('keranjang.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
            ← Kembali ke Keranjang
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><strong>Oops! Ada beberapa kesalahan:</strong></p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="checkout-form" method="POST" action="{{ route('keranjang.process') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl shadow-lg step-card active">
                    <div class="step-header p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white"><i class="fas fa-receipt text-lg"></i></div>
                            <div class="ml-4"><h2 class="text-xl font-bold text-gray-800">Detail Pesanan</h2><p class="text-sm text-gray-500">{{ $keranjangs->sum('jumlah') }} item</p></div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4 max-h-60 overflow-y-auto pr-2">
                            @foreach($keranjangs as $item)
                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}" alt="{{ $item->produk->nama_produk }}" class="w-16 h-16 rounded-lg object-cover mr-4">
                                    <div><div class="font-semibold text-gray-800">{{ $item->produk->nama_produk }}</div><div class="text-sm text-gray-500">{{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</div></div>
                                </div>
                                <div class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg step-card active">
                    <div class="step-header p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white"><i class="fas fa-map-marker-alt text-lg"></i></div>
                            <div class="ml-4"><h2 class="text-xl font-bold text-gray-800">Alamat Pengiriman</h2><p class="text-sm text-gray-500">Pilih alamat tujuan</p></div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($alamats->count() > 0)
                            <div class="space-y-3 mb-4">
                                @foreach($alamats as $alamat)
                                <label class="block cursor-pointer">
                                    <input type="radio" name="alamat_id" value="{{ $alamat->id }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="p-4 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition hover:bg-gray-50">
                                        <p class="font-semibold text-gray-800">{{ $alamat->label }} - {{ $alamat->nama_penerima }}</p>
                                        <p class="text-sm text-gray-600">{{ $alamat->detail_alamat }}, {{ $alamat->kota }}, {{ $alamat->provinsi }}</p>
                                        <p class="text-sm text-gray-500">{{ $alamat->nomor_hp }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <a href="{{ route('alamat.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 text-sm font-medium"><i class="fas fa-plus mr-1"></i> Kelola Alamat</a>
                        @else
                            <div class="text-center py-8 border-dashed border-2 rounded-lg border-gray-300">
                                <i class="fas fa-map-marker-alt text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500 mb-4">Anda belum memiliki alamat tersimpan.</p>
                                <a href="{{ route('alamat.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg text-sm hover:bg-green-700 transition"><i class="fas fa-plus mr-2"></i>Tambah Alamat Baru</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg step-card active">
                    <div class="step-header p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white"><i class="fas fa-truck text-lg"></i></div>
                            <div class="ml-4"><h2 class="text-xl font-bold text-gray-800">Metode Pengiriman</h2><p class="text-sm text-gray-500">Pilih cara pengiriman</p></div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="block cursor-pointer">
                                <input type="radio" name="metode_pengiriman" value="diantar" class="sr-only peer" checked data-ongkir="10000">
                                <div class="p-6 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition text-center option-card h-full flex flex-col justify-center items-center hover:border-green-300">
                                    <img src="https://api.iconify.design/material-symbols:local-shipping-outline.svg?color=%2310b981" alt="Diantar" class="h-16 w-16 mb-3">
                                    <p class="font-semibold text-gray-800">Diantar ke Alamat</p><p class="text-sm text-gray-600 mt-1">Rp 10.000</p>
                                </div>
                            </label>
                            <label class="block cursor-pointer">
                                <input type="radio" name="metode_pengiriman" value="jemput" class="sr-only peer" data-ongkir="0">
                                <div class="p-6 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition text-center option-card h-full flex flex-col justify-center items-center hover:border-green-300">
                                    <img src="https://api.iconify.design/material-symbols:storefront-outline.svg?color=%2310b981" alt="Jemput" class="h-16 w-16 mb-3">
                                    <p class="font-semibold text-gray-800">Jemput di Lokasi</p><p class="text-sm text-gray-600 mt-1">Gratis</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg step-card active">
                    <div class="step-header p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white"><i class="fas fa-credit-card text-lg"></i></div>
                            <div class="ml-4"><h2 class="text-xl font-bold text-gray-800">Metode Pembayaran</h2><p class="text-sm text-gray-500">Pilih cara pembayaran</p></div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="block cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="cod" class="sr-only peer" checked>
                                <div class="p-6 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition text-center option-card h-full flex flex-col justify-center items-center hover:border-green-300">
                                    <svg class="h-12 w-12 mb-2 text-green-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><circle cx="12" cy="12" r="9" /><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1" /><path d="M12 6v2m0 8v2" /></svg>
                                    <p class="font-semibold text-gray-800">Cash on Delivery</p><p class="text-sm text-gray-600 mt-1">Bayar di Tempat</p>
                                </div>
                            </label>
                            <label class="block cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="transfer" class="sr-only peer">
                                <div class="p-6 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition text-center option-card h-full flex flex-col justify-center items-center hover:border-green-300">
                                    <img src="https://api.iconify.design/material-symbols:account-balance-outline.svg?color=%2310b981" alt="Transfer" class="h-16 w-16 mb-3">
                                    <p class="font-semibold text-gray-800">Transfer Bank</p><p class="text-sm text-gray-600 mt-1">Via Payment Gateway</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg step-card active">
                    <div class="step-header p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white"><i class="fas fa-pencil-alt text-lg"></i></div>
                            <div class="ml-4"><h2 class="text-xl font-bold text-gray-800">Catatan</h2><p class="text-sm text-gray-500">Tambahkan catatan khusus (opsional)</p></div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <textarea name="catatan" rows="4" placeholder="Contoh: Tolong packing yang aman, kirim pagi hari..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6 text-gray-800">Ringkasan Belanja</h2>
                    
                    {{-- NOTIFIKASI ERROR/INFO BARU --}}
                    <div id="checkout-notification" class="hidden mb-4 p-4 text-sm rounded-lg" role="alert">
                        <span id="notification-message"></span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Punya Kode Promo?</label>
                        <select name="promo_id" id="promo-select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                            <option value="" data-potongan="0" data-minimum="0">-- Tidak pakai promo --</option>
                            @foreach($promos as $promo)
                            <option value="{{ $promo->id }}" data-potongan="{{ $promo->besaran_potongan }}" data-minimum="{{ $promo->minimum_belanja }}">{{ $promo->nama_promo }} ({{ $promo->besaran_potongan }}%)</option>
                            @endforeach
                        </select>
                        <p id="promo-error" class="text-red-500 text-xs mt-2 hidden"></p>
                    </div>
                    <div class="border-t pt-4 space-y-3">
                        <div class="flex justify-between items-center"><span class="text-gray-600">Subtotal</span><span id="subtotal" data-value="{{ $totalHarga }}" class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Ongkos Kirim</span><span id="ongkir" data-value="10000" class="font-semibold">Rp 10.000</span></div>
                        <div class="flex justify-between items-center text-green-600"><span>Diskon Promo</span><span id="diskon" data-value="0" class="font-semibold">- Rp 0</span></div>
                        <div class="border-t pt-3 mt-3"><div class="flex justify-between items-center"><span class="text-lg font-bold text-gray-800">Total</span><span id="grand-total" class="text-xl font-bold text-green-600">Rp {{ number_format($totalHarga + 10000, 0, ',', '.') }}</span></div></div>
                    </div>

                    <button type="submit" id="process-checkout-btn" class="w-full mt-8 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-105 disabled:bg-gray-400 disabled:transform-none" {{ $alamats->count() == 0 ? 'disabled' : '' }}>
                        <span id="button-text">{{ $alamats->count() == 0 ? 'Tambah Alamat Dulu' : 'Selesaikan Pesanan' }}</span>
                        <div id="button-spinner" class="hidden w-5 h-5 border-t-2 border-r-2 border-white rounded-full animate-spin mx-auto"></div>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const processButton = document.getElementById('process-checkout-btn');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');

    const subtotalEl = document.getElementById('subtotal');
    const ongkirEl = document.getElementById('ongkir');
    const diskonEl = document.getElementById('diskon');
    const grandTotalEl = document.getElementById('grand-total');
    const promoSelect = document.getElementById('promo-select');
    const promoErrorEl = document.getElementById('promo-error');

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'error') {
        const notificationDiv = document.getElementById('checkout-notification');
        const messageSpan = document.getElementById('notification-message');
        
        notificationDiv.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-yellow-100', 'text-yellow-700');
        
        if (type === 'error') {
            notificationDiv.classList.add('bg-red-100', 'text-red-700');
            messageSpan.textContent = 'Error! ' + message;
        } else if (type === 'warning') {
            notificationDiv.classList.add('bg-yellow-100', 'text-yellow-700');
            messageSpan.textContent = 'Peringatan! ' + message;
        }

        notificationDiv.classList.remove('hidden');
    }

    function formatRupiah(angka) {
        return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function calculateTotal() {
        const subtotal = parseFloat(subtotalEl.dataset.value) || 0;
        const ongkir = parseFloat(ongkirEl.dataset.value) || 0;
        const selectedPromo = promoSelect.options[promoSelect.selectedIndex];
        const potonganPersen = parseFloat(selectedPromo.dataset.potongan) || 0;
        const minimum = parseFloat(selectedPromo.dataset.minimum) || 0;
        let diskon = 0;
        promoErrorEl.classList.add('hidden');
        if (potonganPersen > 0) {
            if (subtotal >= minimum) {
                diskon = (subtotal * potonganPersen) / 100;
            } else {
                promoErrorEl.textContent = 'Belanja minimal ' + formatRupiah(minimum) + ' untuk promo ini.';
                promoErrorEl.classList.remove('hidden');
            }
        }
        diskonEl.dataset.value = diskon;
        diskonEl.textContent = '- ' + formatRupiah(diskon);
        const grandTotal = subtotal + ongkir - diskon;
        grandTotalEl.textContent = formatRupiah(grandTotal);
    }

    form.querySelectorAll('input[name="metode_pengiriman"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const ongkirValue = parseFloat(this.dataset.ongkir);
            ongkirEl.dataset.value = ongkirValue;
            ongkirEl.textContent = (ongkirValue > 0) ? formatRupiah(ongkirValue) : 'Gratis';
            calculateTotal();
        });
        if(radio.checked) radio.dispatchEvent(new Event('change'));
    });

    promoSelect.addEventListener('change', calculateTotal);
    calculateTotal();

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        buttonText.classList.add('hidden');
        buttonSpinner.classList.remove('hidden');
        processButton.disabled = true;

        const formData = new FormData(form);
        const plainFormData = Object.fromEntries(formData.entries());
        const formDataJsonString = JSON.stringify(plainFormData);

        fetch('{{ route("keranjang.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formDataJsonString
        })
        .then(response => {
             if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Terjadi kesalahan pada server.');
                });
            }
            return response.json();
        })
        .then(data => {
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
            processButton.disabled = false;

            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = `/pesanan/success/${data.order_id}?status=success`;
                    },
                    onPending: function(result) {
                        window.location.href = `/pesanan/success/${data.order_id}?status=pending`;
                    },
                    onError: function(result) {
                        showNotification('Pembayaran gagal. Silakan coba lagi atau periksa detail pembayaran Anda.', 'error');
                    },
                    onClose: function() {
                        showNotification('Anda menutup jendela pembayaran. Pesanan Anda disimpan dengan status pending.', 'warning');
                    }
                });
            } else if (data.redirect_url) {
                window.location.href = data.redirect_url;
            } else if (data.error) {
                showNotification(data.error, 'error');
            }
        })
        .catch(error => {
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
            processButton.disabled = false;
            
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi.', 'error');
        });
    });

    // Mengubah tombol 'Selesaikan Pesanan' dari type="button" menjadi type="submit" agar event listener di atas bisa berjalan.
    const payButton = document.getElementById('process-checkout-btn');
    payButton.addEventListener('click', function() {
        form.dispatchEvent(new Event('submit', { cancelable: true }));
    });
});
</script>
@endpush