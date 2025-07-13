@extends('layouts.profile') {{-- Ganti layout lama dengan layout profil baru --}}

@section('profile-content') {{-- Bungkus konten dengan section baru --}}
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Alamat Pengiriman</h2>
        <a href="{{ route('alamat.create') }}" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition">
            + Tambah Alamat
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @forelse($alamats as $alamat)
        <div class="bg-gray-50 border rounded-lg p-4 mb-4">
            <div class="font-semibold capitalize">{{ $alamat->label }}</div>
            <div class="text-md font-bold">{{ $alamat->nama_penerima }}</div>
            <div>{{ $alamat->nomor_hp }}</div>
            <div>{{ $alamat->detail_alamat }}, {{ $alamat->kota }}, {{ $alamat->provinsi }}</div>

            <div class="mt-2 flex gap-2">
                <a href="{{ route('alamat.edit', $alamat->id) }}" class="bg-blue-500 text-white px-3 py-1 text-sm rounded-md hover:bg-blue-600">Edit</a>
                <form action="{{ route('alamat.destroy', $alamat->id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 text-sm rounded-md hover:bg-red-600">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-500 text-center py-8">Belum ada alamat disimpan.</p>
    @endforelse
@endsection
