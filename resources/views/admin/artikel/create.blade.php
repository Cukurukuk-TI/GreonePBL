@extends('admin.artikel.layout')

@section('artikel_content')
    <h3 class="text-xl font-semibold mb-4">Tulis Artikel Baru</h3>

    <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kolom Kiri -->
            <div class="md:col-span-2 space-y-4">
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Isi Konten -->
                <div>
                    <label for="konten" class="block text-sm font-medium text-gray-700">Isi Konten</label>
                    <textarea name="konten" id="konten" rows="10"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('konten') }}</textarea>
                </div>
            </div>

            <!-- Kolom Kanan (Sidebar) -->
            <div class="space-y-4">
                <!-- Kategori -->
                <div>
                    <label for="kategori_artikel_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="kategori_artikel_id" id="kategori_artikel_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriArtikels as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_artikel_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Nama Author</label>
                    <input type="text" name="author" id="author" value="{{ old('author', auth()->user()->name) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Tanggal Post -->
                <div>
                    <label for="tanggal_post" class="block text-sm font-medium text-gray-700">Tanggal Post</label>
                    <input type="date" name="tanggal_post" id="tanggal_post" value="{{ old('tanggal_post', date('Y-m-d')) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="status_published" name="status" type="radio" value="published" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                            <label for="status_published" class="ml-3 block text-sm font-medium text-gray-700">Published</label>
                        </div>
                        <div class="flex items-center">
                            <input id="status_draft" name="status" type="radio" value="draft" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="status_draft" class="ml-3 block text-sm font-medium text-gray-700">Draft</label>
                        </div>
                    </div>
                </div>

                <!-- Gambar -->
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                    <input type="file" name="gambar" id="gambar"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan Artikel
                    </button>
                </div>

            </div>
        </div>
    </form>
@endsection
