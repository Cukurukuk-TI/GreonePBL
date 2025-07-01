@extends('layouts.admindashboard')

@section('title', 'Pesanan Dibatalkan')

@section('content')
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-red-700">Daftar Pesanan Dibatalkan</h1>
            <p class="text-sm text-brand-text-muted mt-1">Daftar semua pesanan yang telah dibatalkan.</p>
        </div>
        <a href="{{ route('admin.pesanans.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-white hover:bg-gray-100 border border-gray-300 text-brand-text font-medium rounded-lg text-sm transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Pesanan Aktif
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kartu untuk Tabel Pesanan Dibatalkan --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                {{-- PENYESUAIAN UI: Header Tabel Tematik --}}
                <thead class="bg-red-50 text-xs text-red-800 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID Pesanan</th>
                        <th scope="col" class="px-6 py-3">Pelanggan</th>
                        <th scope="col" class="px-6 py-3">Detail Pesanan</th>
                        <th scope="col" class="px-6 py-3">Tanggal Dibatalkan</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-brand-text font-mono">{{ $pesanan->kode_pesanan }}</p>
                                <p class="text-xs text-brand-text-muted">Dipesan pada: {{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d M Y') }}</p>
                            </td>
                            <td class="px-6 py-4 font-medium text-brand-text">{{ $pesanan->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-brand-text-muted">
                                {{ $pesanan->produk->nama_produk ?? 'N/A' }}
                                <span class="font-semibold text-brand-text">({{ $pesanan->jumlah }}x)</span>
                            </td>
                            <td class="px-6 py-4 text-brand-text-muted">{{ \Carbon\Carbon::parse($pesanan->updated_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                {{-- PENYESUAIAN UI: Tombol Aksi Didesain Ulang --}}
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{route('admin.pesanans.restore', $pesanan->id)}}" method="POST" onsubmit="return confirm('Yakin ingin memulihkan pesanan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        {{-- <button type="submit" class="p-2 rounded-full hover:bg-green-100 text-green-600 transition-colors" title="Pulihkan Pesanan">
                                            <i class="fas fa-undo-alt"></i>
                                        </button> --}}
                                    </form>
                                    <form action="{{route('admin.pesanans.force-delete', $pesanan->id)}}" method="POST" onsubmit="return confirm('PERINGATAN: Tindakan ini akan menghapus data secara permanen dan tidak dapat dibatalkan. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors" title="Hapus Permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16 text-brand-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-gray-300"></i>
                                    <span class="font-medium">Tidak ada pesanan yang dibatalkan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
            <div class="p-6 border-t">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
@endsection