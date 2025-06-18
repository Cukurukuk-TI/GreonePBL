@extends('layouts.admindashboard')

@section('content')
<div class="container-fluid pt-14 p-6">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Arsip Pelanggan</h1>
        <p class="text-gray-500 mt-1">Daftar pelanggan yang telah dihapus sementara.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Data Terhapus</h2>
            <a href="{{ route('admin.pelanggan.index') }}" class="text-blue-600 hover:underline text-sm font-semibold">
                &larr; Kembali ke Daftar Utama
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dihapus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pelanggan as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->deleted_at->isoFormat('D MMMM YYYY, HH:mm') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium flex items-center space-x-4">
                            {{-- Tombol Restore --}}
                            <form action="{{ route('admin.pelanggan.restore', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:text-green-900">Pulihkan</button>
                            </form>
                            {{-- Tombol Hapus Permanen --}}
                            <form action="{{ route('admin.pelanggan.forceDelete', $user->id) }}" method="POST" onsubmit="return confirm('ANDA YAKIN? Data ini akan dihapus permanen dan tidak bisa dikembalikan lagi!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus Permanen</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-gray-500">
                            <p>Tidak ada data pelanggan di dalam arsip.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $pelanggan->links() }}
        </div>
    </div>
</div>
@endsection
