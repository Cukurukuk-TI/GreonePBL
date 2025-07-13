@extends('layouts.appnoslider')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-6xl mx-auto p-4 md:p-6 pb-32" x-data="{ showDeleteModal: false, deletingItem: null }">

        <!-- Modal Konfirmasi Penghapusan - Enhanced -->
        <div x-show="showDeleteModal"
            x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4 backdrop-blur-sm"
             @click.self="showDeleteModal = false">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Penghapusan</h3>
                    </div>
                    <button @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 mb-6 leading-relaxed">Apakah Anda yakin ingin menghapus produk ini dari keranjang?</p>
                <div class="flex justify-end space-x-3">
                    <button @click="showDeleteModal = false"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                        Batal
                    </button>
                    <form :action="`/keranjang/${deletingItem}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-lg">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Header dengan animasi -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707H19M7 13v4a2 2 0 002 2h2a2 2 0 002-2v-1a2 2 0 00-2-2H9a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        Keranjang Belanja
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola produk yang akan Anda beli</p>
                </div>
            </div>
        </div>

        {{-- Flash Messages - Enhanced --}}
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Jika ada isi keranjang --}}
        @if($keranjangs->count() > 0)

            {{-- Mobile View - Enhanced --}}
            <div class="md:hidden space-y-4">
                @foreach($keranjangs as $item)
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300 p-4 border border-gray-100">
                    <div class="flex gap-4">
                        <!-- Product Image with overlay -->
                        <div class="relative">
                            <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}"
                                class="w-24 h-24 object-cover rounded-xl shadow-sm" alt="{{ $item->produk->nama_produk }}">
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ $item->jumlah }}</span>
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col">
                            <!-- Product Info -->
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 text-left mb-1 leading-tight">{{ $item->produk->nama_produk }}</h3>
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $item->produk->kategori->nama_kategori }}</span>
                                </div>
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Stok: {{ $item->produk->stok_produk }}
                                    </span>
                                </div>
                                <div class="text-lg font-bold text-orange-600 mb-3">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between">
                                <!-- Quantity Control -->
                                <form method="POST" action="{{ route('keranjang.update', $item->id) }}" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                        <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors font-bold"
                                                onclick="decreaseQuantity({{ $item->id }})">−</button>
                                        <input type="number" name="jumlah" id="quantity-{{ $item->id }}"
                                               value="{{ $item->jumlah }}" min="1" max="{{ $item->produk->stok_produk }}"
                                               class="w-16 text-center border-none bg-transparent focus:outline-none focus:bg-white font-semibold"
                                               onchange="this.form.submit()">
                                        <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors font-bold"
                                                onclick="increaseQuantity({{ $item->id }}, {{ $item->produk->stok_produk }})">+</button>
                                    </div>
                                </form>

                                <!-- Subtotal and Delete -->
                                <div class="flex flex-col items-end">
                                    <div class="text-lg font-bold text-green-600 mb-1">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                    <button @click="showDeleteModal = true; deletingItem = {{ $item->id }}"
                                            class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center space-x-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Desktop View - Enhanced --}}
            <div class="hidden md:block bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($keranjangs as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-6">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}"
                                                 class="h-20 w-20 rounded-xl object-cover shadow-sm border border-gray-200" alt="">
                                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">{{ $item->jumlah }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-6">
                                            <div class="text-base font-bold text-gray-900 mb-1">{{ $item->produk->nama_produk }}</div>
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $item->produk->kategori->nama_kategori }}</span>
                                            </div>
                                            <div class="text-sm text-gray-500 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                Stok: {{ $item->produk->stok_produk }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="text-lg font-bold text-orange-600">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <form method="POST" action="{{ route('keranjang.update', $item->id) }}" class="flex items-center">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                            <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition-colors font-bold"
                                                    onclick="decreaseQuantity({{ $item->id }})">−</button>
                                            <input type="number" name="jumlah" id="quantity-${id}"
                                                   value="{{ $item->jumlah }}" min="1" max="{{ $item->produk->stok_produk }}"
                                                   class="w-20 text-center border-none bg-transparent focus:outline-none focus:bg-white font-semibold"
                                                   onchange="this.form.submit()">
                                            <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition-colors font-bold"
                                                    onclick="increaseQuantity({{ $item->id }}, {{ $item->produk->stok_produk }})">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm font-medium">
                                    <button @click="showDeleteModal = true; deletingItem = {{ $item->id }}"
                                            class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Clear Cart Button --}}
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-t border-gray-100">
                    <button @click="showDeleteModal = true; deletingItem = 'clear'"
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>

            {{-- Total Fixed Bottom - Enhanced --}}
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-2xl z-50 backdrop-blur-sm">
                <div class="max-w-6xl mx-auto px-4 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-600">Total Belanja</div>
                                <div class="text-2xl font-bold bg-gradient-to-r from-green-600 to-green-700 bg-clip-text text-transparent">
                                    Rp{{ number_format($totalHarga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('keranjang.checkout') }}"
                           class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-2xl text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707H19M7 13v4a2 2 0 002 2h2a2 2 0 002-2v-1a2 2 0 00-2-2H9a2 2 0 00-2 2z"/>
                            </svg>
                            <span>Beli Sekarang</span>
                        </a>
                    </div>
                </div>
            </div>

        @else
            {{-- Empty Cart - Enhanced --}}
            <div class="text-center py-16 bg-white rounded-2xl shadow-lg">
                <div class="max-w-md mx-auto">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707H19M7 13v4a2 2 0 002 2h2a2 2 0 002-2v-1a2 2 0 00-2-2H9a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Keranjang Belanja Kosong</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Belum ada produk di keranjang Anda. Mulai berbelanja dan temukan produk-produk menarik untuk ditambahkan ke keranjang.
                    </p>
                    <div class="space-y-4">
                        <a href="{{ url('/produk') }}"
                           class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Mulai Berbelanja
                        </a>
                        <div class="flex justify-center space-x-4 pt-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">Produk<br>Berkualitas</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">Pengiriman<br>Cepat</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">Harga<br>Terjangkau</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function increaseQuantity(id, maxStock) {
    const input = document.getElementById(`quantity-${id}`);
    const currentValue = parseInt(input.value);
    if (currentValue < maxStock) {
        input.value = currentValue + 1;
        input.form.submit();
    }
}

function decreaseQuantity(id) {
    const input = document.getElementById(`quantity-${id}`);
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        input.form.submit();
    }
}
</script>
@endsection
