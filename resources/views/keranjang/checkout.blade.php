@extends('layouts.appnoslider')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Checkout</h1>
        <a href="{{ route('keranjang.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            ← Kembali ke Keranjang
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Checkout -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Informasi Pengiriman</h2>
            
            <form method="POST" action="{{ route('keranjang.process') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemesan *</label>
                    <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan', Auth::user()->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    @error('nama_pemesan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                    <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    @error('nomor_telepon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman *</label>
                    <textarea name="alamat_pengiriman" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              required>{{ old('alamat_pengiriman') }}</textarea>
                    @error('alamat_pengiriman')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-md">
                    Buat Pesanan
                </button>
            </form>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>
            
            <div class="space-y-4 mb-4">
                @foreach($keranjangs as $item)
                <div class="flex items-center justify-between py-2 border-b">
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . $item->produk->gambar_produk) }}" 
                             alt="{{ $item->produk->nama_produk }}"
                             class="w-12 h-12 rounded object-cover mr-3">
                        <div>
                            <div class="font-medium">{{ $item->produk->nama_produk }}</div>
                            <div class="text-sm text-gray-500">{{ $item->jumlah }}x</div>
                        </div>
                    </div>
                    <div class="font-medium text-green-600">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Subtotal:</span>
                    <span class="font-medium">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Ongkir:</span>
                    <span class="font-medium">Gratis</span>
                </div>
                <div class="border-t pt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Total:</span>
                        <span class="text-lg font-bold text-green-600">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection