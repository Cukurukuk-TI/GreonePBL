@extends('layouts.admindashboard')

@section('title', 'Manajemen Promo')

@section('content')
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-brand-text">Manajemen Promo</h1>
            <p class="text-sm text-brand-text-muted mt-1">Buat dan kelola semua promo diskon untuk pelanggan.</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kartu untuk Form Tambah/Edit Promo --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md mb-8">
        {{-- Meng-include file form.blade.php yang sudah kita buat modern --}}
        @include('admin.promos.form', ['promo' => $editPromo ?? null])
    </div>

    {{-- Tombol untuk beralih kembali ke mode "Tambah" jika sedang dalam mode "Edit" --}}
    @if (isset($editPromo))
        <div class="mb-6">
            <a href="{{ route('admin.promos.index') }}" class="inline-block bg-brand-green hover:bg-brand-green-dark text-white font-semibold px-5 py-2 rounded-lg shadow-sm transition-transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Tambah Promo Baru
            </a>
        </div>
    @endif

    {{-- Kartu untuk Tabel Daftar Promo --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold text-brand-text">Daftar Promo Tersedia</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-brand-text-muted uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Promo</th>
                        <th scope="col" class="px-6 py-3 text-center">Potongan</th>
                        <th scope="col" class="px-6 py-3">Min. Belanja</th>
                        <th scope="col" class="px-6 py-3">Periode Aktif</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($promos as $promo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-brand-text">{{ $promo->nama_promo }}</p>
                                <p class="text-xs text-brand-text-muted truncate" title="{{ $promo->deskripsi_promo }}">{{ Str::limit($promo->deskripsi_promo, 40) }}</p>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-xl text-brand-green">{{ $promo->besaran_potongan }}%</td>
                            <td class="px-6 py-4 text-brand-text-muted">Rp{{ number_format($promo->minimum_belanja, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-brand-text-muted">
                                {{ \Carbon\Carbon::parse($promo->tanggal_mulai)->translatedFormat('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($promo->tanggal_selesai)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{-- Logika Status Badge --}}
                                @if($promo->is_active && now()->between($promo->tanggal_mulai, $promo->tanggal_selesai))
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Aktif</span>
                                @elseif($promo->is_active && now()->lt($promo->tanggal_mulai))
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Akan Datang</span>
                                @else
                                    <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Toggle Status --}}
                                    <form action="{{ route('admin.promos.toggle-status', $promo->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 rounded-full {{ $promo->is_active ? 'text-yellow-500 hover:bg-yellow-100' : 'text-green-500 hover:bg-green-100' }}" title="{{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas {{ $promo->is_active ? 'fa-toggle-on fa-lg' : 'fa-toggle-off fa-lg' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.promos.edit', $promo->id) }}" class="p-2 rounded-full text-blue-600 hover:bg-blue-100" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form id="delete-form-{{ $promo->id }}" action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                            onclick="confirmDelete('{{ $promo->id }}')" 
                                            class="p-2 rounded-full text-red-600 hover:bg-red-100" 
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-brand-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-tags fa-3x mb-3 text-gray-300"></i>
                                    <span class="font-medium">Belum ada promo yang ditambahkan.</span>
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