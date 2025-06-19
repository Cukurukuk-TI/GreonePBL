@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold leading-tight">Tulis Artikel Baru</h2>
        <a href="{{ route('admin.artikel.index') }}" class="text-gray-600 hover:text-gray-800">
            &larr; Kembali ke Daftar Artikel
        </a>
    </div>

    <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Utama -->
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="konten" class="block text-sm font-medium text-gray-700">Isi Konten</label>
                        <textarea name="konten" id="konten" rows="15"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('konten') }}</textarea>
                        @error('konten')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md space-y-6">
                    <div>
                        <label for="kategori_artikel_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="kategori_artikel_id" id="kategori_artikel_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriArtikels as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_artikel_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_artikel_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                        <input type="text" name="author" id="author" value="{{ old('author', auth()->user()->name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tanggal_post" class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
                        <input type="date" name="tanggal_post" id="tanggal_post" value="{{ old('tanggal_post', date('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('tanggal_post')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="published" @if(old('status') == 'published') selected @endif>Published</option>
                            <option value="draft" @if(old('status') == 'draft') selected @endif>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                        <input type="file" name="gambar" id="gambar"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('gambar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-5">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Simpan Artikel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
