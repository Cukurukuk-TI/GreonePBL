@extends('layouts.alamat')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
        <div class="text-sm text-gray-500">
            Total: {{ $pesanans->count() }} pesanan
        </div>
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

    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'pending' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Menunggu
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'proses']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'proses' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Diproses
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'dikirim']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'dikirim' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Dikirim
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'complete']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'complete' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Selesai
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Dibatalkan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pesanans as $pesanan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 align-top">
                                    {{-- Loop melalui setiap detail item dalam satu pesanan --}}
                                    @foreach($pesanan->detailPesanans as $detail)
                                        <div class="flex items-center space-x-4 @if(!$loop->last) mb-3 pb-3 border-b border-gray-200 @endif">
                                            <div class="flex-shrink-0">
                                                @if($detail->produk && $detail->produk->gambar_produk)
                                                    <img src="{{ asset('storage/' . $detail->produk->gambar_produk) }}"
                                                        alt="{{ $detail->produk->nama_produk }}"
                                                        class="w-16 h-16 object-cover rounded-lg border">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 rounded-lg border flex items-center justify-center text-gray-400">?</div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Jumlah: {{ $detail->jumlah }}x
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="text-xs text-gray-400 mt-2 font-mono">
                                        Kode: {{ $pesanan->kode_pesanan }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    {{-- Kode untuk menampilkan status (sudah benar, tidak perlu diubah) --}}
                                    @switch($pesanan->status_pesanan)
                                        @case('pending') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Menunggu</span> @break
                                        @case('diproses') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Diproses</span> @break
                                        @case('dikirim') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dikirim</span> @break
                                        @case('complete') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span> @break
                                        @case('dibatalkan') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span> @break
                                    @endswitch
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                                    <div>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('H:i') }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium align-top">
                                    {{-- Kode untuk tombol testimoni dan detail (sudah benar, tidak perlu diubah) --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
        <div class="mt-6">
            {{ $pesanans->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<div id="orderDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
            <button onclick="closeOrderDetail()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="orderDetailContent">
            </div>
    </div>
</div>

<script>
function showOrderDetail(orderId) {
    // Implementasi untuk menampilkan detail pesanan
    // Bisa menggunakan AJAX atau data yang sudah ada
    const modal = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailContent');

    // Contoh konten statis, bisa diganti dengan AJAX call
    content.innerHTML = `
        <div class="space-y-3">
            <div>
                <span class="font-medium">Kode Pesanan:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
            <div>
                <span class="font-medium">Metode Pengiriman:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
            <div>
                <span class="font-medium">Metode Pembayaran:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
            <div>
                <span class="font-medium">Alamat Pengiriman:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeOrderDetail() {
    const modal = document.getElementById('orderDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('orderDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderDetail();
    }
});
</script>
@endsection
