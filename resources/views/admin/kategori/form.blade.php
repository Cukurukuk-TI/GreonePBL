{{-- resources/views/admin/kategoris/form.blade.php --}}

<h2 class="text-xl font-bold text-brand-text mb-6">
    {{ isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
</h2>

<form 
    action="{{ isset($kategori) ? route('admin.kategori.update', $kategori->id) : route('admin.kategori.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
>
    @csrf
    @if(isset($kategori))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
        
        <div class="flex flex-col gap-6">
            <div>
                <label for="nama_kategori" class="block text-sm font-medium text-brand-text mb-1">Nama Kategori</label>
                <input 
                    type="text" 
                    id="nama_kategori" 
                    name="nama_kategori"
                    value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
                    placeholder="Contoh: Sayur"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('nama_kategori') border-red-500 @enderror"
                />
                @error('nama_kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-brand-text mb-1">Deskripsi</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="8"
                    placeholder="Masukkan Deskripsi Kategori....."
                    class="w-full border-gray-300 rounded-lg shadow-sm resize-none focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('deskripsi') border-red-500 @enderror"
                >{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-brand-text mb-1">Gambar</label>
            
            <label 
                id="drop-zone"
                for="gambar_kategori"
                class="mt-1 flex justify-center items-center w-full h-64 px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-brand-green transition-colors duration-200"
            >
                <div id="placeholder-content" class="text-center {{ (isset($kategori) && $kategori->gambar_kategori) ? 'hidden' : '' }}">
                    <i class="fas fa-cloud-upload-alt fa-3x text-gray-400"></i>
                    <p class="mt-2 text-sm text-brand-text-muted">
                        <span class="font-semibold">Drop your images here!</span>
                    </p>
                    <p class="text-xs text-gray-500">atau klik untuk memilih file</p>
                </div>

                <img 
                    id="image-preview"
                    src="{{ isset($kategori) && $kategori->gambar_kategori ? asset('storage/' . $kategori->gambar_kategori) : '' }}" 
                    alt="Preview Gambar"
                    class="max-h-full max-w-full rounded-md {{ !(isset($kategori) && $kategori->gambar_kategori) ? 'hidden' : '' }}"
                >
            </label>

            <input type="file" id="gambar_kategori" name="gambar_kategori" class="hidden">
            @error('gambar_kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
        @if(isset($kategori))
            <a href="{{ route('admin.kategori.index') }}" class="text-sm text-brand-text-muted hover:underline">Batal</a>
        @endif
        <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold px-12 py-3 rounded-lg shadow-md transition-transform hover:scale-105">
            {{ isset($kategori) ? 'Update' : 'Simpan' }}
        </button>
    </div>
</form>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('gambar_kategori');
    const imagePreview = document.getElementById('image-preview');
    const placeholderContent = document.getElementById('placeholder-content');

    // Membuat area drop zone bisa di-klik untuk membuka file dialog
    // Ini sudah otomatis ditangani oleh tag <label for="..."></label>

    // --- Event Listener untuk Drag & Drop ---

    // Mencegah perilaku default browser saat file di-drag ke area
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-brand-green', 'bg-green-50');
    });

    // Mengembalikan style saat file meninggalkan area drop
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-brand-green', 'bg-green-50');
    });

    // Menangani file yang di-drop
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-brand-green', 'bg-green-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            // Tempatkan file yang di-drop ke dalam input file
            fileInput.files = files;
            // Panggil fungsi untuk menampilkan preview
            displayPreview(files[0]);
        }
    });

    // Menangani file yang dipilih lewat klik
    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files.length > 0) {
            displayPreview(files[0]);
        }
    });

    // --- Fungsi untuk Menampilkan Preview ---
    function displayPreview(file) {
        // Pastikan file yang dipilih adalah gambar
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                placeholderContent.classList.add('hidden');
                imagePreview.classList.remove('hidden');
            };
            
            reader.readAsDataURL(file);
        } else {
            // Jika file bukan gambar, jangan tampilkan preview
            // (Anda bisa menambahkan notifikasi error di sini jika perlu)
            alert('File yang dipilih harus berupa gambar!');
        }
    }
</script>