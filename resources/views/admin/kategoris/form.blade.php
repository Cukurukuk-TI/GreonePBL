<form 
    action="{{ isset($kategori) ? route('admin.kategoris.update', $kategori->id) : route('admin.kategoris.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
    class=""
>
    @csrf
    @if(isset($kategori))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kolom Kiri --}}
        <div>
            {{-- Nama Kategori --}}
            <div class="mb-4">
                <label for="nama_kategori" class="block font-semibold mb-1">Nama Kategori</label>
                <input 
                    type="text" 
                    id="nama_kategori" 
                    name="nama_kategori"
                    value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
                    placeholder="Contoh: Sayur"
                    class="w-full border px-3 py-2 rounded @error('nama_kategori') border-red-500 @enderror focus:outline-none focus:border-blue-400"
                />
                @error('nama_kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label for="deskripsi" class="block font-semibold mb-1">Deskripsi</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="4"
                    placeholder="Masukkan Deskripsi Kategori..."
                    class="w-full border px-3 py-2 rounded resize-none @error('deskripsi') border-red-500 @enderror focus:outline-none focus:border-blue-400"
                >{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div>
            {{-- Upload Gambar --}}
            <div class="mb-4">
                <label for="gambar_kategori" class="block font-semibold mb-1">Gambar</label>
                <div class="border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center h-32 mb-2 relative">
                    <input 
                        type="file" 
                        id="gambar_kategori" 
                        name="gambar_kategori"
                        class="absolute w-full h-full opacity-0 cursor-pointer"
                    />
                    <div class="flex flex-col items-center pointer-events-none">
                        <svg class="w-8 h-8 text-green-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="text-gray-400">Drop your image here</span>
                    </div>
                </div>
                @error('gambar_kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if(isset($kategori) && $kategori->gambar_kategori)
                    <img 
                        src="{{ asset('storage/' . $kategori->gambar_kategori) }}" 
                        alt="Preview Gambar Kategori" 
                        class="h-16 w-auto rounded border border-gray-300"
                    />
                @endif
            </div>
        </div>
    </div>

    {{-- Tombol Submit --}}
    <div class="flex justify-end mt-4">
        <button 
            type="submit" 
            class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold px-12 py-2 rounded"
        >
            {{ isset($kategori) ? 'Update' : 'Simpan' }}
        </button>
        @if(isset($kategori))
            <a href="{{ route('admin.kategoris.index') }}" class="ml-4 mt-2 md:mt-0 text-sm text-gray-600 hover:underline">Batal</a>
        @endif
    </div>
</form>
