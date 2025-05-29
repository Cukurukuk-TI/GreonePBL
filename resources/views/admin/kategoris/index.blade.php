@extends('layouts.admindashboard')

@section('content')
    <div class="container mx-auto px-4 pt-14">
        <h1 class="text-2xl font-bold mb-6">Manajemen Kategori</h1>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Tambah/Edit Kategori --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-10">
            @include('admin.kategoris.form')
        </div>

        {{-- Tabel Kategori --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2">No</th>
                        <th class="border px-3 py-2">Gambar</th>
                        <th class="border px-3 py-2">Nama Kategori</th>
                        <th class="border px-3 py-2">Deskripsi</th>
                        <th class="border px-3 py-2">Jumlah Produk</th>
                        <th class="border px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kategori)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $index + 1 }}</td>
                            <td class="border px-3 py-2">
                                @if ($kategori->gambar_kategori)
                                    <img src="{{ asset('storage/' . $kategori->gambar_kategori) }}" width="60" class="rounded">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2">{{ $kategori->nama_kategori }}</td>
                            <td class="border px-3 py-2">{{ $kategori->deskripsi }}</td>
                            <td class="border px-3 py-2">{{ $kategori->produks_count ?? 0 }}</td>
                            <td class="border px-3 py-2 space-x-1">
                                <a href="{{ route('admin.kategoris.edit', $kategori->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                    Edit
                                </a>

                                <form action="{{ route('admin.kategoris.destroy', $kategori->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                            <td colspan="6" class="text-center text-gray-500 py-4">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
