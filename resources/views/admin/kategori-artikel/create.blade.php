@extends('admin.artikel.layout')

@section('artikel_content')
    <h3 class="text-xl font-semibold mb-4">Tambah Kategori Baru</h3>

    <form action="{{ route('admin.artikel.kategori.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
            <input type="text" id="nama" name="nama"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('nama') border-red-500 @enderror"
                   value="{{ old('nama') }}" required>
            @error('nama')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Simpan
            </button>
            <a href="{{ route('admin.artikel.kategori.index') }}" class="text-gray-600 hover:text-gray-800 ml-3">
                Batal
            </a>
        </div>
    </form>
@endsection
