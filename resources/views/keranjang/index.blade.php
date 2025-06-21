@extends('layouts.appnoslider')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="flex items-center">
                <i class="fas fa-shopping-cart text-3xl text-brand-green mr-4"></i>
                <h1 class="text-3xl font-bold text-brand-text">Keranjang Belanja Anda</h1>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($keranjangs->isNotEmpty())
        <form method="POST" action="{{ route('checkout.pilih') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- Kolom Kiri: Daftar Produk --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-4 sm:p-6 space-y-6">
                    @foreach($keranjangs as $item)
                    <div class="flex flex-col sm:flex-row gap-4 border-b pb-6 last:border-b-0 last:pb-0 items-center">

                        {{-- Checkbox --}}
                        <div class="flex-shrink-0">
                            <input type="checkbox"
                                   name="produk_terpilih[]"
                                   value="{{ $item->id }}"
                                   class="form-checkbox produk-checkbox text-green-600 w-5 h-5 mt-2 sm:mt-0"
                                   data-subtotal="{{ $item->subtotal }}">
                        </div>

                        {{-- Gambar Produk --}}
                        <div class="flex-shrink-0">
                            <img src="{{ $item->produk->gambar_produk ? asset('storage/' . $item->produk->gambar_produk) : 'https://placehold.co/150x150' }}"
                                alt="{{ $item->produk->nama_produk }}"
                                class="w-28 h-28 object-cover rounded-lg border border-gray-300">
                        </div>

                        {{-- Konten Produk --}}
                        <div class="flex flex-col sm:flex-row justify-between items-center w-full gap-6">

                            {{-- Info --}}
                            <div class="flex-1 text-left">
                                <a href="{{ route('produk.show', $item->produk->id) }}"
                                    class="text-lg font-semibold text-brand-text hover:text-brand-green block">
                                    {{ $item->produk->nama_produk }}
                                </a>
                                <p class="text-2xl text-orange-500 font-bold mt-1">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex items-center gap-6">
                                {{-- Hapus --}}
                                <form method="POST" action="{{ route('keranjang.destroy', $item->id) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-gray-400 hover:text-red-600 transition duration-150 text-xl"
                                        title="Hapus produk dari keranjang">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                {{-- Update Jumlah --}}
                                <form method="POST" action="{{ route('keranjang.update', $item->id) }}" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white shadow-sm">
                                        <button type="button" onclick="decreaseQuantity({{ $item->id }})"
                                            id="decrease-{{ $item->id }}"
                                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                                            <svg width="12" height="2" viewBox="0 0 12 2" fill="none">
                                                <path d="M0 1H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <input type="number" name="jumlah" id="quantity-{{ $item->id }}" value="{{ $item->jumlah }}"
                                            min="1" max="{{ $item->produk->stok_produk }}"
                                            class="w-12 h-8 text-center text-sm font-medium border-0 focus:outline-none bg-transparent"
                                            onchange="this.form.submit()" readonly>
                                        <button type="button" onclick="increaseQuantity({{ $item->id }}, {{ $item->produk->stok_produk }})"
                                            id="increase-{{ $item->id }}"
                                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                <path d="M6 0V12M0 6H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Kolom Kanan: Ringkasan --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-brand-text border-b pb-4 mb-4">Ringkasan Belanja</h2>

                        <div class="space-y-3">
                            <div class="flex justify-between text-brand-text-muted">
                                <span>Subtotal</span>
                                <span id="total-harga">Rp0</span>
                            </div>
                            <div class="flex justify-between text-brand-text-muted">
                                <span>Ongkos Kirim</span>
                                <span id="ongkir">Rp0</span>
                            </div>
                        </div>

                        <div class="border-t my-4"></div>
                        <div class="flex justify-between font-bold text-lg text-brand-text">
                            <span>Total</span>
                            <span id="grand-total">Rp0</span>
                        </div>

                        {{-- Tombol Checkout --}}
                        <div class="mt-6">
                            <a href="{{ route('keranjang.checkout') }}"
                               class="w-full inline-block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Lanjutkan ke Checkout
                            </a>
                        </div>

                        {{-- Kosongkan --}}
                        <div class="mt-4 text-center">
                            <form method="POST" action="{{ route('keranjang.clear') }}"
                                  onsubmit="return confirm('Yakin ingin mengosongkan seluruh keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full inline-block border border-red-300 hover:border-red-800 text-red-700 py-3 px-6 rounded-lg transition">
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            function formatRupiah(angka) {
                return 'Rp' + angka.toLocaleString('id-ID');
            }

            function updateRingkasan() {
                let total = 0;

                document.querySelectorAll('.produk-checkbox:checked').forEach(checkbox => {
                    const subtotal = parseInt(checkbox.dataset.subtotal) || 0;
                    total += subtotal;
                });

                const ongkir = total > 0 ? 10000 : 0;

                document.getElementById('total-harga').innerText = formatRupiah(total);
                document.getElementById('ongkir').innerText = formatRupiah(ongkir);
                document.getElementById('grand-total').innerText = formatRupiah(total + ongkir);
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.produk-checkbox').forEach(cb => {
                    cb.addEventListener('change', updateRingkasan);
                });

                updateRingkasan(); // jalankan saat awal load
            });

            function increaseQuantity(id, maxStock) {
                const input = document.getElementById(`quantity-${id}`);
                let value = parseInt(input.value);
                if (value < maxStock) {
                    input.value = value + 1;
                    input.form.submit();
                }
            }

            function decreaseQuantity(id) {
                const input = document.getElementById(`quantity-${id}`);
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    input.form.submit();
                }
            }
        </script>
        @else
            {{-- Tampilan ketika keranjang kosong --}}
            <div class="bg-white rounded-xl shadow-md text-center p-16">
                <i class="fas fa-cart-arrow-down fa-4x text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-800">Keranjang Anda masih kosong</h2>
                <p class="text-gray-500 mt-2 mb-6">Sepertinya Anda belum menambahkan produk apapun ke keranjang.</p>
                <a href="{{ url('/') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-md transition-transform hover:scale-105">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>
@endsection