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
    {{-- Upload Gambar Kategori --}}
    <div class="mb-4">
        <label for="gambar_kategori" class="block font-semibold mb-1">Gambar Kategori</label>

        <div class="w-full border border-gray-300 rounded px-3 py-2 flex items-center justify-between">
            <label for="gambar_kategori"
                class="cursor-pointer inline-block bg-blue-500 hover:bg-blue-600 text-sm text-white font-semibold py-2 px-4 rounded">
                Pilih Gambar
            </label>

            <span id="nama-file" class="text-sm text-gray-600 truncate ml-4">
                Belum ada file dipilih
            </span>
        </div>

        <input 
            type="file" 
            id="gambar_kategori" 
            name="gambar_kategori" 
            accept="image/*" 
            class="hidden" 
            onchange="previewGambarKategori(event)"
        >

        <img 
            id="preview-gambar-kategori"
            src="{{ isset($kategori) && $kategori->gambar_kategori ? asset('storage/' . $kategori->gambar_kategori) : '' }}" 
            class="mt-4 h-24 w-auto rounded border border-gray-300 {{ isset($kategori) && $kategori->gambar_kategori ? '' : 'hidden' }}"
        >

        @error('gambar_kategori')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
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
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview-gambar-kategori');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
