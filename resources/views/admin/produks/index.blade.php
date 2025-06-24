@extends('layouts.admindashboard')

@section('title', 'Manajemen Produk')

@section('content')
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-brand-text">Manajemen Produk</h1>
            <p class="text-sm text-brand-text-muted mt-1">Tambah dan kelola semua Produk yang tersedia.</p>
        </div>
    </div>


    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Kartu Form Tambah/Edit --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">
            {{ isset($editProduk) ? 'Edit Produk' : 'Tambah Produk' }}
        </h2>

        {{-- Panggil form.blade.php --}}
        @include('admin.produks.form', [
            'produk' => $editProduk ?? null,
            'kategoris' => $kategoris,
        ])
    </div>

    {{-- Daftar Produk --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold text-brand-text">Daftar Produk</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-brand-text-muted uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3">Nama Produk</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3 text-center">Stok</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produks as $produk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-brand-text">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($produk->gambar_produk)
                                    <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                                        alt="{{ $produk->nama_produk }}" class="w-16 h-16 object-cover rounded-md">
                                @else
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-brand-text">{{ $produk->nama_produk }}</td>
                            <td class="px-6 py-4 text-brand-text-muted">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4 text-brand-text">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center font-medium">{{ $produk->stok_produk }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    {{-- Edit dari halaman index --}}
                                    <a href="{{ route('admin.produks.index', ['edit' => $produk->id]) }}"
                                        class="font-medium text-blue-600 hover:text-blue-800" title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-800"
                                            title="Hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-brand-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <span>Belum ada produk yang ditambahkan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
