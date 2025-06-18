<h2 class="text-xl font-bold text-brand-text mb-6">
    {{ isset($produk) ? 'Edit Produk' : 'Tambah Produk Baru' }}
</h2>

<form 
    action="{{ isset($produk) ? route('admin.produk.update', $produk->id) : route('admin.produk.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
>
    @csrf
    @if(isset($produk))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
        <div class="flex flex-col gap-y-6">
            <div>
                <label for="nama_produk" class="block text-sm font-medium text-brand-text mb-1">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" placeholder="Contoh: Selada Hidroponik" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="id_kategori" class="block text-sm font-medium text-brand-text mb-1">Kategori</label>
                <select id="id_kategori" name="id_kategori" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('id_kategori', $produk->id_kategori ?? '') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
             <div>
                <label for="deskripsi_produk" class="block text-sm font-medium text-brand-text mb-1">Deskripsi</label>
                <textarea id="deskripsi_produk" name="deskripsi_produk" rows="6" placeholder="Masukkan Deskripsi Produk......." class="w-full border-gray-300 rounded-lg shadow-sm resize-none focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50">{{ old('deskripsi_produk', $produk->deskripsi_produk ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex flex-col gap-y-6">
            <div>
                <label for="harga_produk" class="block text-sm font-medium text-brand-text mb-1">Harga</label>
                <input type="number" id="harga_produk" name="harga_produk" value="{{ old('harga_produk', $produk->harga_produk ?? '') }}" placeholder="Contoh: 15000" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="stok_produk" class="block text-sm font-medium text-brand-text mb-1">Stok</label>
                <input type="number" id="stok_produk" name="stok_produk" value="{{ old('stok_produk', $produk->stok_produk ?? '') }}" placeholder="Contoh: 100" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring focus:ring-green-200 focus:ring-opacity-50">
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-text mb-1">Gambar Produk</label>
                <label id="drop-zone-produk" for="gambar_produk" class="mt-1 flex justify-center items-center w-full h-full min-h-[10rem] px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-brand-green transition-colors duration-200">
                    <div id="placeholder-content-produk" class="text-center {{ (isset($produk) && $produk->gambar_produk) ? 'hidden' : '' }}">
                        <i class="fas fa-cloud-upload-alt fa-3x text-gray-400"></i>
                        <p class="mt-2 text-sm text-brand-text-muted">
                            <span class="font-semibold">Drop your images here!</span>
                        </p>
                        <p class="text-xs text-gray-500">atau klik untuk memilih file</p>
                    </div>
                    <img id="image-preview-produk" src="{{ isset($produk) && $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : '' }}" alt="Preview Produk" class="max-h-48 max-w-full rounded-md {{ !(isset($produk) && $produk->gambar_produk) ? 'hidden' : '' }}">
                </label>
                <input type="file" id="gambar_produk" name="gambar_produk" class="hidden">
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
        @if(isset($produk))
            <a href="{{ route('admin.produk.index') }}" class="text-sm text-brand-text-muted hover:underline">Batal</a>
        @endif
        <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold px-12 py-3 rounded-lg shadow-md transition-transform hover:scale-105">
            {{ isset($produk) ? 'Update Produk' : 'Simpan' }}
        </button>
    </div>
</form>

<script>
    const dropZoneProduk = document.getElementById('drop-zone-produk');
    const fileInputProduk = document.getElementById('gambar_produk');
    const imagePreviewProduk = document.getElementById('image-preview-produk');
    const placeholderContentProduk = document.getElementById('placeholder-content-produk');

    dropZoneProduk.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZoneProduk.classList.add('border-brand-green', 'bg-green-50');
    });
    dropZoneProduk.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZoneProduk.classList.remove('border-brand-green', 'bg-green-50');
    });
    dropZoneProduk.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZoneProduk.classList.remove('border-brand-green', 'bg-green-50');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInputProduk.files = files;
            displayPreviewProduk(files[0]);
        }
    });
    fileInputProduk.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files.length > 0) {
            displayPreviewProduk(files[0]);
        }
    });

    function displayPreviewProduk(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewProduk.src = e.target.result;
                placeholderContentProduk.classList.add('hidden');
                imagePreviewProduk.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>