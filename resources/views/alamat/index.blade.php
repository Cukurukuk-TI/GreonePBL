{{-- resources/views/alamat/index.blade.php --}}
@extends('layouts.alamat')

@section('alamat-content')
<div class="mb-4 flex justify-between items-center">
    <form action="{{ route('alamat.index') }}" method="GET">
        <input type="text" name="search" class="border rounded px-3 py-2" placeholder="Cari nama penerima..." value="{{ request('search') }}">
    </form>
    <a href="{{ route('alamat.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ Tambah Alamat</a>
</div>

@forelse($alamats as $alamat)
    <div class="bg-green-50 border rounded-lg p-4 mb-4">
        <div class="font-semibold capitalize">{{ $alamat->label }}</div>
        <div class="text-md font-bold">{{ $alamat->nama_penerima }}</div>
        <div>{{ $alamat->nomor_hp }}</div>
        <div>{{ $alamat->provinsi }}, {{ $alamat->kota }}</div>
        <div>{{ $alamat->detail_alamat }}</div>

        <div class="mt-2 flex gap-2">
            <a href="{{ route('alamat.edit', $alamat->id) }}" class="bg-green-500 text-white px-3 py-1 rounded">Perbarui Alamat</a>
            <form action="{{ route('alamat.destroy', $alamat->id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="border border-red-500 text-red-500 px-3 py-1 rounded">Hapus</button>
            </form>
        </div>
    </div>
@empty
    <p class="text-gray-500">Belum ada alamat disimpan.</p>
@endforelse
@endsection
