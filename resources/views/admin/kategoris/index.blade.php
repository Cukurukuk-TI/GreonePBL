@extends('layouts.admindashboard')

@section('title', 'Manajemen Kategori')

@section('content')
    
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-brand-text">Manajemen Kategori</h1>
            <p class="text-sm text-brand-text-muted mt-1">Buat dan kelola semua daftar kategori.</p>
        </div>
    </div>
    {{-- Notifikasi akan muncul di sini --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Kartu untuk Form Tambah/Edit Kategori --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md mb-8">
        {{-- 
          PERBAIKAN PENTING:
          Meneruskan variabel `$kategoriToEdit` dari controller ke dalam form.
          Jika tidak dalam mode edit, variabel ini akan bernilai null.
        --}}
        @include('admin.kategoris.form', ['kategori' => $kategoriToEdit ?? null])
    </div>
 

    {{-- Kartu untuk Tabel Daftar Kategori --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold text-brand-text">Daftar Kategori</h2>
        </div>
        
        <div class="overflow-x-auto">
            {{-- PENYESUAIAN UI: Tabel dibuat lebih modern --}}
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-brand-text-muted uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-center">Jumlah Produk</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoris as $kategori)
                        <tr class="hover:bg-gray-50">
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
                            <td class="px-6 py-4 text-brand-text-muted max-w-sm truncate" title="{{ $kategori->deskripsi }}">
                                {{ $kategori->deskripsi ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-center font-medium">{{ $kategori->produks_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    <a href="{{ route('admin.kategoris.edit', $kategori->id) }}" class="font-medium text-blue-600 hover:text-blue-800" title="Edit">
                                        Edit
                                    </a>
                                    <form id="delete-form-{{ $kategori->id }}" action="{{ route('admin.kategoris.destroy', $kategori->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button"
                                        onclick="confirmDelete({{ $kategori->id }})"
                                        class="font-medium text-red-600 hover:text-red-800" title="Hapus">
                                        Hapus
                                    </button>
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
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection