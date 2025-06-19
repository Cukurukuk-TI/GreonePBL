@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold leading-tight">Edit Artikel</h2>
        <a href="{{ route('admin.artikel.index') }}" class="text-gray-600 hover:text-gray-800">
            &larr; Kembali ke Daftar Artikel
        </a>
    </div>

    <form action="{{ route('admin.artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Penting: Method untuk update --}}

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $artikel->judul) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="konten" class="block text-sm font-medium text-gray-700">Isi Konten</label>
                        <textarea name="konten" id="konten" rows="15"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('konten', $artikel->konten) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md space-y-6">
                    <div>
                        <label for="kategori_artikel_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="kategori_artikel_id" id="kategori_artikel_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($kategoriArtikels as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_artikel_id', $artikel->kategori_artikel_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                        <input type="text" name="author" id="author" value="{{ old('author', $artikel->author) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label for="tanggal_post" class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
                        <input type="date" name="tanggal_post" id="tanggal_post" value="{{ old('tanggal_post', $artikel->tanggal_post->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="published" @if(old('status', $artikel->status) == 'published') selected @endif>Published</option>
                            <option value="draft" @if(old('status', $artikel->status) == 'draft') selected @endif>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700">Ganti Gambar</label>
                        @if($artikel->gambar)
                            <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Gambar saat ini" class="my-2 w-full h-auto rounded">
                        @endif
                        <input type="file" name="gambar" id="gambar"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="pt-5">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                            Update Artikel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
