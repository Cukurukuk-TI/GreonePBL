<form 
    action="{{ isset($produk) ? route('admin.produks.update', $produk->id) : route('admin.produks.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
    class=""
>
    @csrf
    @if(isset($produk))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kolom Kiri --}}
        <div>
            <div class="mb-4">
                <label for="nama_produk" class="block font-semibold mb-1">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk"
                    value="{{ old('nama_produk', $produk->nama_produk ?? '') }}"
                    placeholder="Contoh : Selada"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400" />
            </div>
            <div class="mb-4">
                <label for="harga_produk" class="block font-semibold mb-1">Harga</label>
                <input type="number" id="harga_produk" name="harga_produk"
                    value="{{ old('harga_produk', $produk->harga_produk ?? '') }}"
                    placeholder="Contoh : 15000"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400" />
            </div>
            <div class="mb-4">
                <label for="deskripsi_produk" class="block font-semibold mb-1">Deskripsi</label>
                <textarea id="deskripsi_produk" name="deskripsi_produk" rows="4"
                    placeholder="Masukkan Deskripsi Produk......."
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 resize-none">{{ old('deskripsi_produk', $produk->deskripsi_produk ?? '') }}</textarea>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div>
            <div class="mb-4">
                <label for="id_kategori" class="block font-semibold mb-1">Kategori</label>
                <select id="id_kategori" name="id_kategori"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('id_kategori', $produk->id_kategori ?? '') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="stok_produk" class="block font-semibold mb-1">Stok</label>
                <input type="number" id="stok_produk" name="stok_produk"
                    value="{{ old('stok_produk', $produk->stok_produk ?? '') }}"
                    placeholder="Contoh : 100"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400" />
            </div>

                        {{-- upload gambar --}}
            <div class="mb-4">
                <label for="gambar_produk" class="block font-semibold mb-1">Gambar Produk</label>

                <div class="w-full border border-gray-300 rounded px-3 py-2 flex items-center justify-between">
                    <label for="gambar_produk"
                        class="cursor-pointer inline-block bg-blue-500 hover:bg-blue-600 text-sm text-white font-semibold py-2 px-4 rounded">
                        Pilih Gambar
                    </label>

                    <span id="file-name" class="text-sm text-gray-600 truncate ml-4">Belum ada file dipilih</span>
                </div>

                <input type="file" id="gambar_produk" name="gambar_produk" accept="image/*"
                    class="hidden">

                <img id="preview-image"
                    src="{{ isset($produk) && $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : '' }}"
                    class="mt-4 h-24 w-auto rounded border border-gray-300 {{ isset($produk) && $produk->gambar_produk ? '' : 'hidden' }}">
            </div>
        </div>
    </div>


    <div class="flex justify-end mt-4">
        <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold px-12 py-2 rounded">
            Simpan
        </button>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('gambar_produk');
        const preview = document.getElementById('preview-image');
        const fileName = document.getElementById('file-name');

        input.addEventListener('change', function (e) {
            const file = e.target.files[0];

            if (file) {
                fileName.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                fileName.textContent = 'Belum ada file dipilih';
                preview.src = '';
                preview.classList.add('hidden');
            }
        });
    });
</script>
