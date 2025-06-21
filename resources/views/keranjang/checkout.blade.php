@extends('layouts.appnoslider')

@section('title', 'Checkout')

@section('content')
<style>
    .checkout-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .checkout-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .form-section {
        padding: 24px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    
    .section-number {
        background: #28a745;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        margin-right: 10px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.25);
    }
    
    .method-option {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .method-option:hover {
        border-color: #28a745;
        background-color: #f8fff9;
    }
    
    .method-option.selected {
        border-color: #28a745;
        background-color: #f8fff9;
    }
    
    .method-info {
        display: flex;
        align-items: center;
    }
    
    .method-icon {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 18px;
    }
    
    .method-icon.pickup {
        background-color: #e8f5e8;
        color: #28a745;
    }
    
    .method-icon.delivery {
        background-color: #e3f2fd;
        color: #2196f3;
    }
    
    .method-icon.cod {
        background-color: #fff3e0;
        color: #ff9800;
    }
    
    .method-icon.transfer {
        background-color: #e8eaf6;
        color: #3f51b5;
    }
    
    .method-text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 4px 0;
    }
    
    .method-text p {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }
    
    .method-price {
        font-weight: 600;
        color: #28a745;
    }
    
    .summary-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 24px;
        position: sticky;
        top: 20px;
    }
    
    .product-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .product-item:last-child {
        border-bottom: none;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 12px;
        border: 1px solid #e9ecef;
    }
    
    .product-details h5 {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 4px 0;
    }
    
    .product-details .quantity-price {
        font-size: 12px;
        color: #6c757d;
    }
    
    .product-total {
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        margin-left: auto;
    }
    
    .price-breakdown {
        border-top: 1px solid #e9ecef;
        padding-top: 16px;
        margin-top: 16px;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .price-row.total {
        font-weight: 700;
        font-size: 16px;
        color: #2c3e50;
        border-top: 1px solid #e9ecef;
        padding-top: 12px;
        margin-top: 12px;
    }
    
    .btn-checkout {
        width: 100%;
        background: #28a745;
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-top: 20px;
    }
    
    .btn-checkout:hover:not(:disabled) {
        background: #218838;
    }
    
    .btn-checkout:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }
    
    .stock-warning {
        background: #fff3cd;
        color: #856404;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 12px;
        margin-top: 8px;
        border: 1px solid #ffeaa7;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    
    .page-header p {
        color: #6c757d;
        font-size: 16px;
    }
    
    .breadcrumb-nav {
        background: white;
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .breadcrumb-nav a {
        color: #28a745;
        text-decoration: none;
        font-size: 14px;
    }
    
    .breadcrumb-nav a:hover {
        text-decoration: underline;
    }
    
    .breadcrumb-nav span {
        color: #6c757d;
        margin: 0 8px;
    }
</style>

<div class="checkout-container">
    <div class="container mx-auto max-w-6xl px-4">
        
        {{-- Breadcrumb --}}
        <div class="breadcrumb-nav">
            <a href="{{ route('home') }}">Beranda</a>
            <span>›</span>
            <a href="{{ route('keranjang.index') }}">Keranjang</a>
            <span>›</span>
            <span>Checkout</span>
        </div>

        {{-- Page Header --}}
        <div class="page-header">
            <h1>Checkout</h1>
            <p>Lengkapi informasi untuk menyelesaikan pesanan Anda</p>
        </div>

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('keranjang.process') }}" id="checkout-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column --}}
                <div class="lg:col-span-2">
                    <div class="checkout-card">
                        
                        {{-- Customer Info --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="section-number">1</div>
                                Informasi Pemesan
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" 
                                           name="nama_pemesan" 
                                           value="{{ old('nama_pemesan', Auth::user()->name ?? '') }}" 
                                           class="form-control"
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                    @error('nama_pemesan')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon *</label>
                                    <input type="tel" 
                                           name="nomor_telepon" 
                                           value="{{ old('nomor_telepon') }}" 
                                           class="form-control"
                                           placeholder="08xxxxxxxxxx"
                                           required>
                                    @error('nomor_telepon')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="section-number">2</div>
                                Alamat Pengiriman
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="alamat_pengiriman" 
                                          rows="4" 
                                          class="form-control"
                                          placeholder="Masukkan alamat lengkap termasuk kode pos"
                                          required>{{ old('alamat_pengiriman') }}</textarea>
                                @error('alamat_pengiriman')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Catatan Pesanan (Opsional)</label>
                                <textarea name="catatan" 
                                          rows="3" 
                                          class="form-control"
                                          placeholder="Tambahkan catatan khusus untuk pesanan Anda">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        {{-- Shipping Method --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="section-number">3</div>
                                Metode Pengiriman
                            </div>
                            
                            <div class="method-option selected" data-method="pickup">
                                <div class="method-info">
                                    <div class="method-icon pickup">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div class="method-text">
                                        <h4>Jemput di Lokasi</h4>
                                        <p>Ambil produk langsung di toko</p>
                                    </div>
                                </div>
                                <div class="method-price">GRATIS</div>
                                <input type="radio" name="metode_pengiriman" value="pickup" checked style="display: none;">
                            </div>

                            <div class="method-option" data-method="delivery">
                                <div class="method-info">
                                    <div class="method-icon delivery">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="method-text">
                                        <h4>Diantar ke Alamat</h4>
                                        <p>Produk akan dikirim ke lokasi Anda</p>
                                    </div>
                                </div>
                                <div class="method-price">Rp 10.000</div>
                                <input type="radio" name="metode_pengiriman" value="delivery" style="display: none;">
                            </div>
                            
                            <div class="stock-warning">
                                Stok habis. Pengiriman tidak tersedia.
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="section-number">4</div>
                                Metode Pembayaran
                            </div>
                            
                            <div class="method-option selected" data-payment="cod">
                                <div class="method-info">
                                    <div class="method-icon cod">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                    <div class="method-text">
                                        <h4>COD (Bayar di Tempat)</h4>
                                        <p>Bayar saat produk sampai di tangan Anda</p>
                                    </div>
                                </div>
                                <input type="radio" name="metode_pembayaran" value="cod" checked style="display: none;">
                            </div>

                            <div class="method-option" data-payment="transfer">
                                <div class="method-info">
                                    <div class="method-icon transfer">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="method-text">
                                        <h4>Transfer Bank</h4>
                                        <p>Lakukan pembayaran via rekening bank</p>
                                    </div>
                                </div>
                                <input type="radio" name="metode_pembayaran" value="transfer" style="display: none;">
                            </div>
                            
                            <div class="stock-warning">
                                Stok habis. Pembayaran tidak tersedia.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Summary --}}
                <div class="lg:col-span-1">
                    <div class="summary-card">
                        <div class="section-title">
                            <div class="section-number">5</div>
                            Ringkasan Pesanan
                        </div>
                        
                        {{-- Product List --}}
                        <div class="space-y-0 mb-4" style="max-height: 300px; overflow-y: auto;">
                            @forelse($keranjangs ?? [] as $item)
                                <div class="product-item">
                                    <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}" 
                                         alt="{{ $item->produk->nama_produk }}" 
                                         class="product-image">
                                    <div class="product-details">
                                        <h5>{{ $item->produk->nama_produk }}</h5>
                                        <div class="quantity-price">
                                            {{ $item->jumlah }} x Rp{{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                        </div>
                                        @if(($item->produk->stok ?? 0) <= 0)
                                            <div style="color: #dc3545; font-size: 11px; margin-top: 2px;">
                                                Stok Habis
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-total">
                                        Rp{{ number_format($item->subtotal ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 40px 0; color: #6c757d;">
                                    <p>Keranjang kosong</p>
                                </div>
                            @endforelse
                        </div>
                        
                        {{-- Price Summary --}}
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span>Subtotal</span>
                                <span>Rp{{ number_format($totalHarga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="price-row">
                                <span>Total Ongkos Kirim</span>
                                <span id="shipping-cost">Rp10.000</span>
                            </div>
                            <div class="price-row" style="color: #28a745;">
                                <span>Diskon</span>
                                <span>Rp0</span>
                            </div>
                            <div class="price-row total">
                                <span>Grand Total</span>
                                <span id="grand-total">Rp{{ number_format(($totalHarga ?? 0) + 10000, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-checkout" id="checkout-btn">
                            <span id="btn-text">Stok Habis - Tidak Dapat Dipesan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = {{ $totalHarga ?? 0 }};
    const shippingCostEl = document.getElementById('shipping-cost');
    const grandTotalEl = document.getElementById('grand-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const btnText = document.getElementById('btn-text');
    
    // Check if has stock
    const hasStock = {{ ($keranjangs ?? collect())->every(function($item) { return ($item->produk->stok ?? 0) > 0; }) ? 'true' : 'false' }};
    
    if (hasStock) {
        checkoutBtn.disabled = false;
        btnText.textContent = 'Buat Pesanan Sekarang';
        checkoutBtn.style.background = '#28a745';
    } else {
        checkoutBtn.disabled = true;
        btnText.textContent = 'Stok Habis - Tidak Dapat Dipesan';
        checkoutBtn.style.background = '#6c757d';
    }

    // Handle shipping method selection
    document.querySelectorAll('.method-option[data-method]').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected from all shipping methods
            document.querySelectorAll('.method-option[data-method]').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected to clicked option
            this.classList.add('selected');
            
            // Update radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update costs
            if (this.dataset.method === 'pickup') {
                shippingCostEl.textContent = 'Rp0';
                grandTotalEl.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
            } else {
                shippingCostEl.textContent = 'Rp10.000';
                grandTotalEl.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal + 10000);
            }
        });
    });

    // Handle payment method selection
    document.querySelectorAll('.method-option[data-payment]').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected from all payment methods
            document.querySelectorAll('.method-option[data-payment]').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected to clicked option
            this.classList.add('selected');
            
            // Update radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });

    // Form validation
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        if (!hasStock) {
            e.preventDefault();
            alert('Tidak dapat melakukan checkout karena ada produk yang stoknya habis.');
            return false;
        }
    });
});
</script>
@endsection