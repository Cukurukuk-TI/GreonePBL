@extends('layouts.admindashboard')

@section('content')
    <div class="container mx-auto px-4 pt-14">
        <h1 class="text-2xl font-bold mb-6">Manajemen Promo</h1>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Tambah/Edit Promo --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-10">
            <h2 class="text-xl font-semibold mb-4">
                {{ isset($editPromo) ? 'Edit Promo' : 'Tambah Promo' }}
            </h2>

            {{-- Panggil form.blade.php --}}
            @include('admin.promos.form', [
                'promo' => $editPromo ?? null,
            ])
        </div>

        {{-- Tombol Tambah Baru (reset form ke mode tambah) --}}
        @if (isset($editPromo))
            <div class="mb-4">
                <a href="{{ route('admin.promos.index') }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                    + Tambah Promo Baru
                </a>
            </div>
        @endif

        {{-- Tabel Promo --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2">No</th>
                        <th class="border px-3 py-2">Nama Promo</th>
                        <th class="border px-3 py-2">Besaran Potongan</th>
                        <th class="border px-3 py-2">Minimum Belanja</th>
                        <th class="border px-3 py-2">Tanggal Mulai</th>
                        <th class="border px-3 py-2">Tanggal Selesai</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2">Deskripsi</th>
                        <th class="border px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $i => $promo)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $i + 1 }}</td>
                            <td class="border px-3 py-2 font-medium">{{ $promo->nama_promo }}</td>
                            <td class="border px-3 py-2">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                                    {{ $promo->besaran_potongan }}%
                                </span>
                            </td>
                            <td class="border px-3 py-2">{{ $promo->formatted_minimum_belanja }}</td>
                            <td class="border px-3 py-2">{{ $promo->tanggal_mulai->format('d/m/Y') }}</td>
                            <td class="border px-3 py-2">{{ $promo->tanggal_selesai->format('d/m/Y') }}</td>
                            <td class="border px-3 py-2">
                                @if($promo->isValid())
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">
                                        Aktif
                                    </span>
                                @elseif($promo->is_active && $promo->tanggal_mulai > now())
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">
                                        Akan Datang
                                    </span>
                                @elseif($promo->is_active && $promo->tanggal_selesai < now())
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-semibold">
                                        Berakhir
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="border px-3 py-2">{{ Str::limit($promo->deskripsi_promo, 50) }}</td>
                            <td class="border px-3 py-2 space-x-1">
                                <a href="{{ route('admin.promos.index', ['edit' => $promo->id]) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                    Edit
                                </a>
                                <form action="{{ route('admin.promos.toggle-status', $promo->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="bg-{{ $promo->is_active ? 'orange' : 'green' }}-500 hover:bg-{{ $promo->is_active ? 'orange' : 'green' }}-600 text-white px-2 py-1 rounded text-xs">
                                        {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
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
                            <td colspan="9" class="text-center text-gray-500 py-4">Belum ada promo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection