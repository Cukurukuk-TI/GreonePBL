@extends('layouts.admindashboard')

@section('content')
    <div class="container mx-auto px-4 pt-14">
        <h1 class="text-2xl font-bold mb-6">Manajemen Produk</h1>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Tambah/Edit Produk --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-10">
            <h2 class="text-xl font-semibold mb-4">
                {{ isset($editProduk) ? 'Edit Produk' : 'Tambah Produk' }}
            </h2>

            {{-- Panggil form.blade.php --}}
            @include('admin.produks.form', [
                'produk' => $editProduk ?? null,
                'kategoris' => $kategoris,
            ])
        </div>

        {{-- Tombol Tambah Baru (reset form ke mode tambah) --}}
        @if (isset($editProduk))
            <div class="mb-4">
                <a href="{{ route('admin.produks.index') }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                    + Tambah Produk Baru
                </a>
            </div>
        @endif

        {{-- Tabel Produk --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2">No</th>
                        <th class="border px-3 py-2">Gambar</th>
                        <th class="border px-3 py-2">Nama Produk</th>
                        <th class="border px-3 py-2">Harga</th>
                        <th class="border px-3 py-2">Stok</th>
                        <th class="border px-3 py-2">Kategori</th>
                        <th class="border px-3 py-2">Deskripsi</th>
                        <th class="border px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $i => $produk)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $i + 1 }}</td>
                            <td class="border px-3 py-2">
                                @if ($produk->gambar_produk)
                                    <img src="{{ asset('storage/' . $produk->gambar_produk) }}" width="60" class="rounded">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2">{{ $produk->nama_produk }}</td>
                            <td class="border px-3 py-2">Rp{{ number_format($produk->harga_produk, 0, ',', '.') }}</td>
                            <td class="border px-3 py-2">{{ $produk->stok_produk }}</td>
                            <td class="border px-3 py-2">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ Str::limit($produk->deskripsi_produk, 50) }}</td>
                            <td class="border px-3 py-2 space-x-1">
                                <a href="{{ route('admin.produks.index', ['edit' => $produk->id]) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                    Edit
                                </a>
                                <form action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
