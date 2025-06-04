<form 
    action="{{ isset($promo) ? route('admin.promos.update', $promo->id) : route('admin.promos.store') }}" 
    method="POST" 
    class=""
>
    @csrf
    @if(isset($promo))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kolom Kiri --}}
        <div>
            <div class="mb-4">
                <label for="nama_promo" class="block font-semibold mb-1">Nama Promo</label>
                <input type="text" id="nama_promo" name="nama_promo"
                    value="{{ old('nama_promo', $promo->nama_promo ?? '') }}"
                    placeholder="Contoh: Sayur Segar"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('nama_promo') border-red-500 @enderror" />
                @error('nama_promo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="besaran_potongan" class="block font-semibold mb-1">Besaran Potongan (%)</label>
                <input type="number" id="besaran_potongan" name="besaran_potongan"
                    value="{{ old('besaran_potongan', $promo->besaran_potongan ?? '') }}"
                    placeholder="Contoh: 10"
                    min="1" max="100"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('besaran_potongan') border-red-500 @enderror" />
                @error('besaran_potongan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_mulai" class="block font-semibold mb-1">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', isset($promo) ? $promo->tanggal_mulai->format('Y-m-d') : '') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('tanggal_mulai') border-red-500 @enderror" />
                @error('tanggal_mulai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_selesai" class="block font-semibold mb-1">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    value="{{ old('tanggal_selesai', isset($promo) ? $promo->tanggal_selesai->format('Y-m-d') : '') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('tanggal_selesai') border-red-500 @enderror" />
                @error('tanggal_selesai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div>
            <div class="mb-4">
                <label for="minimum_belanja" class="block font-semibold mb-1">Minimum Belanja (Rp)</label>
                <input type="number" id="minimum_belanja" name="minimum_belanja"
                    value="{{ old('minimum_belanja', $promo->minimum_belanja ?? '') }}"
                    placeholder="Contoh: 50000"
                    min="0" step="0.01"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400 @error('minimum_belanja') border-red-500 @enderror" />
                @error('minimum_belanja')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col h-full justify-between">
                <div class="mb-4">
                    <label for="deskripsi_promo" class="block font-semibold mb-1">Deskripsi</label>
                    <textarea id="deskripsi_promo" name="deskripsi_promo" rows="8"
                        placeholder="Masukkan Deskripsi Promo......."
                        class="w-full border border-gray-300 rounded px-3 py-2 pb-4 focus:outline-none focus:border-blue-400 resize-none @error('deskripsi_promo') border-red-500 @enderror">{{ old('deskripsi_promo', $promo->deskripsi_promo ?? '') }}</textarea>
                    @error('deskripsi_promo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit"
            class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold px-12 py-2 rounded">
            Simpan
        </button>
    </div>
</form>
