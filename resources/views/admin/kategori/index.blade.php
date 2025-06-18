@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md mb-8">
        {{-- File form.blade.php yang sudah disempurnakan akan di-include di sini --}}
        @include('admin.kategori.form')
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md">
        <h2 class="text-xl font-bold text-brand-text mb-6">Daftar Kategori</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-brand-text-muted">
                <thead class="text-xs text-brand-text-muted uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-center">Jumlah Produk</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kategori)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-brand-text">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($kategori->gambar_kategori)
                                    <img src="{{ asset('storage/' . $kategori->gambar_kategori) }}" alt="{{ $kategori->nama_kategori }}" class="w-16 h-16 object-cover rounded-md">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-brand-text">{{ $kategori->nama_kategori }}</td>
                            <td class="px-6 py-4 max-w-sm truncate">{{ $kategori->deskripsi ?: '-' }}</td>
                            <td class="px-6 py-4 text-center font-medium">{{ $kategori->produks_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                                        class="font-medium text-blue-600 hover:text-blue-800 transition duration-150">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="font-medium text-red-600 hover:text-red-800 transition duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-brand-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <span>Belum ada kategori yang ditambahkan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection