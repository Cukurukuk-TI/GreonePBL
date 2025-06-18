@extends('layouts.admindashboard')

@section('content')
<div class="container-fluid pt-14 p-6">

    {{-- Header Halaman --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Pelanggan</h1>
        <p class="text-gray-500 mt-1">Kelola semua akun pelanggan yang terdaftar di sistem.</p>
    </div>

    {{-- Dua Card Statistik di Atas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Card Pelanggan Aktif --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center space-x-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users fa-2x text-blue-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pelanggan Aktif</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPelangganAktif }}</p>
            </div>
        </div>

        {{-- Card Pelanggan Dihapus --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center space-x-4">
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-user-slash fa-2x text-red-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pelanggan Dihapus</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPelangganDihapus }}</p>
            </div>
        </div>
    </div>

    {{-- Card Tabel Utama --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Pelanggan Aktif</h2>

                <a href="{{ route('admin.pelanggan.trash') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg text-sm flex items-center">
            <i class="fas fa-archive mr-2"></i>
            Lihat Arsip
        </a>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pelanggan as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-lg font-semibold text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->isoFormat('D MMMM YYYY') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <form action="{{ route('admin.pelanggan.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pelanggan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-gray-500">
                            <p>Tidak ada data pelanggan aktif.</p>
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
