@extends('layouts.appnoslider')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-6 pb-32" x-data="{ showDeleteModal: false, deletingItem: null }">

    <!-- Modal Konfirmasi Penghapusan -->
    <div x-show="showDeleteModal"
        x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
         @click.self="showDeleteModal = false">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Penghapusan</h3>
                <button @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus produk ini dari keranjang?</p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <form :action="`/keranjang/${deletingItem}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Keranjang Belanja</h1>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Jika ada isi keranjang --}}
    @if($keranjangs->count() > 0)

<div class="md:hidden space-y-4">
    @foreach($keranjangs as $item)
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex gap-4">
            <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}" 
                class="w-24 h-24 object-cover rounded-lg" alt="{{ $item->produk->nama_produk }}">
            <div class="flex-1 flex flex-col">
                <!-- Bagian Atas - Informasi Produk -->
                <div class="flex-1">
                    <div class="font-semibold text-gray-800 text-left">{{ $item->produk->nama_produk }}</div>
                    <div class="text-sm text-gray-500 text-left">{{ $item->produk->kategori->nama_kategori }}</div>
                    <div class="text-xs text-gray-400 text-left mb-2">Stok: {{ $item->produk->stok_produk }}</div>
                    <div class="text-orange-600 font-semibold text-left">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Bagian Bawah - Aksi dan Subtotal -->
                <div class="flex items-end justify-between mt-2">
                    <!-- Input Jumlah -->
                    <form method="POST" action="{{ route('keranjang.update', $item->id) }}" class="flex items-center">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center border rounded">
                            <button type="button" class="px-2 py-1 text-gray-600 hover:bg-gray-100" 
                                    onclick="decreaseQuantity({{ $item->id }})">−</button>
                            <input type="number" name="jumlah" id="quantity-{{ $item->id }}" 
                                   value="{{ $item->jumlah }}" min="1" max="{{ $item->produk->stok_produk }}"
                                   class="w-14 text-center border-none focus:outline-none"
                                   onchange="this.form.submit()">
                            <button type="button" class="px-2 py-1 text-gray-600 hover:bg-gray-100" 
                                    onclick="increaseQuantity({{ $item->id }}, {{ $item->produk->stok_produk }})">+</button>
                        </div>
                    </form>

                    <!-- Subtotal dan Hapus -->
                    <div class="flex flex-col items-end">
                        <div class="text-green-600 font-bold">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                        <button @click="showDeleteModal = true; deletingItem = {{ $item->id }}" 
                                class="text-red-600 hover:text-red-800 text-sm mt-1">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

        {{-- Desktop View --}}
        <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($keranjangs as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}" 
                                         class="h-16 w-16 rounded-lg object-cover" alt="">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->produk->nama_produk }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->produk->kategori->nama_kategori }}</div>
                                        <div class="text-xs text-gray-400">Stok: {{ $item->produk->stok_produk }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-orange-600">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('keranjang.update', $item->id) }}" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex items-center border rounded">
                                        <button type="button" class="px-2 py-1 text-gray-600 hover:text-gray-800" 
                                                onclick="decreaseQuantity({{ $item->id }})">−</button>
                                        <input type="number" name="jumlah" id="quantity-${id}" 
                                               value="{{ $item->jumlah }}" min="1" max="{{ $item->produk->stok_produk }}"
                                               class="w-16 text-center border-none focus:outline-none"
                                               onchange="this.form.submit()">
                                        <button type="button" class="px-2 py-1 text-gray-600 hover:text-gray-800" 
                                                onclick="increaseQuantity({{ $item->id }}, {{ $item->produk->stok_produk }})">+</button>
                                    </div>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button @click="showDeleteModal = true; deletingItem = {{ $item->id }}" 
                                        class="text-red-600 hover:text-red-900">
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

            {{-- Tombol Kosongkan --}}
            <div class="bg-gray-50 px-6 py-4">
                <button @click="showDeleteModal = true; deletingItem = 'clear'" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    Kosongkan Keranjang
                </button>
            </div>
        </div>

        {{-- Total Fixed Bottom --}}
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-50">
            <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
                <div>
                    <div class="text-lg font-bold text-green-600">Total</div>
                    <div class="text-2xl font-bold text-green-600">
                        Rp{{ number_format($totalHarga, 0, ',', '.') }}
                    </div>
                </div>
                <a href="{{ route('keranjang.checkout') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                    Beli
                </a>
            </div>
        </div>

    @else
        {{-- Keranjang Kosong --}}
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707H19M7 13v4a2 2 0 002 2h2a2 2 0 002-2v-1a2 2 0 00-2-2H9a2 2 0 00-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang kosong</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai berbelanja untuk menambahkan produk ke keranjang.</p>
            <div class="mt-6">
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif
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