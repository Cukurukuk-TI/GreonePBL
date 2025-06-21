{{-- Judul Form Dinamis --}}
<h2 class="text-xl font-bold text-brand-text mb-6">
    {{ isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
</h2>

<form
    action="{{ isset($kategori) ? route('admin.kategoris.update', $kategori->id) : route('admin.kategoris.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @if(isset($kategori))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

        <div class="space-y-6">
            {{-- Nama Kategori --}}
            <div>
                <label for="nama_kategori" class="block text-sm font-medium text-brand-text-muted mb-1">Nama Kategori</label>
                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
                    placeholder="Contoh: Sayuran Daun"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200"
                />
                @error('nama_kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-brand-text-muted mb-1">Deskripsi</label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    placeholder="Deskripsi singkat mengenai kategori ini..."
                    class="w-full border-gray-300 rounded-lg shadow-sm resize-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200"
                >{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-brand-text-muted mb-1">Gambar Kategori</label>

            <label
                id="drop-zone-kategori"
                for="gambar_kategori"
                class="mt-1 flex justify-center items-center w-full h-full min-h-[14rem] px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-brand-green transition-colors duration-200"
            >
                <div id="placeholder-kategori" class="text-center {{ (isset($kategori) && $kategori->gambar_kategori) ? 'hidden' : '' }}">
                    <i class="fas fa-cloud-upload-alt fa-3x text-gray-400"></i>
                    <p class="mt-2 text-sm text-brand-text-muted">
                        <span class="font-semibold">Drop gambar di sini</span>
                    </p>
                    <p class="text-xs text-gray-500">atau klik untuk memilih file</p>
                </div>

                <img
                    id="preview-kategori"
                    src="{{ isset($kategori) && $kategori->gambar_kategori ? asset('storage/' . $kategori->gambar_kategori) : '' }}"
                    alt="Preview Kategori"
                    class="max-h-48 max-w-full rounded-md {{ !(isset($kategori) && $kategori->gambar_kategori) ? 'hidden' : '' }}"
                >
            </label>

            <input type="file" id="gambar_kategori" name="gambar_kategori" class="hidden">

            @error('gambar_kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Tombol Aksi Form --}}
    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
        @if(isset($kategori))
            <a href="{{ route('admin.kategoris.index') }}" class="text-sm text-brand-text-muted hover:underline">Batal</a>
        @endif
        <button type="submit" class="w-full sm:w-auto bg-brand-green hover:bg-brand-green-dark text-white font-bold px-8 py-2.5 rounded-lg shadow-md transition-transform hover:scale-105">
            {{ isset($kategori) ? 'Update Kategori' : 'Simpan Kategori' }}
        </button>
    </div>
</form>

<script>
    const dropZoneKategori = document.getElementById('drop-zone-kategori');
    const fileInputKategori = document.getElementById('gambar_kategori');
    const imagePreviewKategori = document.getElementById('preview-kategori');
    const placeholderKategori = document.getElementById('placeholder-kategori');

    // Membuat area drop zone bisa di-klik karena dibungkus <label>

    // Event listener untuk Drag & Drop
    dropZoneKategori.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZoneKategori.classList.add('border-brand-green', 'bg-green-50');
    });

    dropZoneKategori.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZoneKategori.classList.remove('border-brand-green', 'bg-green-50');
    });

    dropZoneKategori.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZoneKategori.classList.remove('border-brand-green', 'bg-green-50');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInputKategori.files = files;
            displayPreviewKategori(files[0]);
        }
    });

    // Event listener untuk klik pilih file
    fileInputKategori.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files.length > 0) {
            displayPreviewKategori(files[0]);
        }
    });

    // Fungsi untuk menampilkan preview
    function displayPreviewKategori(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewKategori.src = e.target.result;
                placeholderKategori.classList.add('hidden');
                imagePreviewKategori.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
