@extends('layouts.appnoslider')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Keranjang Belanja</h1>
    </div>

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

    @if($keranjangs->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($keranjangs as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-lg object-cover" 
                                             src="{{ asset('storage/' . $item->produk->gambar_produk) }}" 
                                             alt="{{ $item->produk->nama_produk }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->produk->nama_produk }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item->produk->kategori->nama_kategori }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            Stok: {{ $item->produk->stok_produk }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-orange-600">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('keranjang.update', $item->id) }}" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex items-center border rounded">
                                        <button type="button" class="px-2 py-1 text-gray-600 hover:text-gray-800" 
                                                onclick="decreaseQuantity({{ $item->id }})">−</button>
                                        <input type="number" name="jumlah" id="quantity-{{ $item->id }}" 
                                               value="{{ $item->jumlah }}" min="1" max="{{ $item->produk->stok_produk }}"
                                               class="w-16 text-center border-none focus:outline-none focus:ring-0"
                                               onchange="this.form.submit()">
                                        <button type="button" class="px-2 py-1 text-gray-600 hover:text-gray-800" 
                                                onclick="increaseQuantity({{ $item->id }}, {{ $item->produk->stok_produk }})">+</button>
                                    </div>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form method="POST" action="{{ route('keranjang.destroy', $item->id) }}" 
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total dan Aksi -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex gap-4">
                        <form method="POST" action="{{ route('keranjang.clear') }}" 
                              onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Total Belanja:</div>
                        <div class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('keranjang.checkout') }}" 
                               class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded">
                                Checkout Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Keranjang Kosong -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707H19M7 13v4a2 2 0 002 2h2a2 2 0 002-2v-1a2 2 0 00-2-2H9a2 2 0 00-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang kosong</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai berbelanja untuk menambahkan produk ke keranjang.</p>
            <div class="mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
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