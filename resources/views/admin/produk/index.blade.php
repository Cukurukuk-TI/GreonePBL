@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Form Tambah/Edit Produk --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md mb-8">
        {{-- Meng-include file form.blade.php yang sudah disempurnakan --}}
        @include('admin.produk.form', [
            'produk' => $editProduk ?? null,
            'kategoris' => $kategoris,
        ])
    </div>

    {{-- Tombol untuk beralih ke mode Tambah Baru (muncul saat mode edit) --}}
    @if (isset($editProduk))
        <div class="mb-6">
            <a href="{{ route('admin.produk.index') }}" class="inline-block bg-brand-green hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg shadow-sm transition-transform hover:scale-105">
                + Tambah Produk Baru
            </a>
        </div>
    @endif

    {{-- Tabel Daftar Produk --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md">
        <h2 class="text-xl font-bold text-brand-text mb-6">Daftar Produk</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-brand-text-muted">
                <thead class="text-xs text-brand-text-muted uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Harga</th>
                        <th scope="col" class="px-6 py-3 text-center">Stok</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $produk)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-brand-text">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($produk->gambar_produk)
                                    <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}" class="w-16 h-16 object-cover rounded-md">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-brand-text">{{ $produk->nama_produk }}</td>
                            <td class="px-6 py-4">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">{{ $produk->stok_produk }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- PERBAIKAN: Link edit kini menggunakan route 'produk.edit' --}}
                                    <a href="{{ route('admin.produk.edit', $produk->id) }}" class="font-medium text-blue-600 hover:text-blue-800 transition">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-800 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-brand-text-muted">
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