@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 pt-14">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-red-600">Daftar Pesanan yang Dibatalkan</h1>
        <a href="{{ route('admin.pesanans.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Pesanan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm text-left">
            <thead class="bg-red-50">
                <tr>
                    <th class="border px-3 py-2">ID Pesanan</th>
                    <th class="border px-3 py-2">Nama Pelanggan</th>
                    <th class="border px-3 py-2">Nama Produk</th>
                    <th class="border px-3 py-2">Jumlah Pesanan</th>
                    <th class="border px-3 py-2">Tanggal Pesanan</th>
                    <th class="border px-3 py-2">Total Harga</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Tanggal Dibatalkan</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
                <tbody>
                    @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2 font-mono">{{ $pesanan->kode_pesanan }}</td>
                            <td class="border px-3 py-2">{{ $pesanan->user->name ?? 'User Dihapus' }}</td>
                            <td class="border px-3 py-2">
                                {{-- Loop untuk produk --}}
                                @foreach($pesanan->detailPesanans as $detail)
                                    <div>{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                @endforeach
                            </td>
                            <td class="border px-3 py-2 text-center">{{ $pesanan->detailPesanans->sum('jumlah') }}</td>
                            <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="border px-3 py-2">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td class="border px-3 py-2">
                                {{-- Kode untuk status (sudah benar, tidak perlu diubah) --}}
                                @switch($pesanan->status)
                                    @case('pending') <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-semibold">Menunggu</span> @break
                                    @case('proses') <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">Di Proses</span> @break
                                    @case('dikirim') <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Di Kirim</span> @break
                                    @case('complete') <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Selesai</span> @break
                                @endswitch
                            </td>
                            <td class="border px-3 py-2">
                                {{-- Kode untuk form update status (sudah benar, tidak perlu diubah) --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-8">
                                Belum ada pesanan aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
        </table>
    </div>

    <!-- Pagination jika menggunakan paginate -->
    @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
        <div class="mt-4">
            {{ $pesanans->links() }}
        </div>
    @endif
</div>
@endsection
