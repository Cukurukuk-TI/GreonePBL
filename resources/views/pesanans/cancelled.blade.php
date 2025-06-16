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
                    <tr class="hover:bg-red-50">
                        <td class="border px-3 py-2 font-mono">{{ $pesanan->kode_pesanan }}</td>
                        <td class="border px-3 py-2">{{ $pesanan->user->name }}</td>
                        <td class="border px-3 py-2">{{ $pesanan->produk->nama_produk }}</td>
                        <td class="border px-3 py-2 text-center">{{ $pesanan->jumlah }}x</td>
                        <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="border px-3 py-2">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        <td class="border px-3 py-2">
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Dibatalkan</span>
                        </td>
                        <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($pesanan->updated_at)->format('d/m/Y H:i') }}</td>
                        <td class="border px-3 py-2">
                            <!-- Opsi untuk mengembalikan pesanan ke status pending jika diperlukan -->
                            <form action="{{ route('admin.pesanans.restore', $pesanan->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan pesanan ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">
                                    Pulihkan
                                </button>
                            </form>
                            
                            <!-- Opsi untuk menghapus permanen -->
                            <form action="{{ route('admin.pesanans.force-delete', $pesanan->id) }}" method="POST" class="inline ml-1" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen? Tindakan ini tidak dapat dibatalkan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                    Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 py-8">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada pesanan yang dibatalkan</p>
                                <p class="text-sm text-gray-400">Semua pesanan masih aktif</p>
                            </div>
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