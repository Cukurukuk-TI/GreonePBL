<h2 class="text-xl font-bold text-brand-text mb-6">
    {{ isset($promo) ? 'Edit Promo' : 'Tambah Promo Baru' }}
</h2>

<form 
    action="{{ isset($promo) ? route('admin.promos.update', $promo->id) : route('admin.promos.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
>
    @csrf
    @if(isset($promo))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
        
        <div class="space-y-6">
            <div>
                <label for="nama_promo" class="block text-sm font-medium text-brand-text-muted mb-1">Nama Promo</label>
                <input type="text" id="nama_promo" name="nama_promo" value="{{ old('nama_promo', $promo->nama_promo ?? '') }}" placeholder="Contoh: Diskon Kemerdekaan"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200">
                @error('nama_promo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="besaran_potongan" class="block text-sm font-medium text-brand-text-muted mb-1">Besaran Potongan (%)</label>
                <input type="number" id="besaran_potongan" name="besaran_potongan" value="{{ old('besaran_potongan', $promo->besaran_potongan ?? '') }}" placeholder="Contoh: 10" min="1" max="100"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200">
                @error('besaran_potongan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="minimum_belanja" class="block text-sm font-medium text-brand-text-muted mb-1">Minimum Belanja (Rp)</label>
                <input type="number" id="minimum_belanja" name="minimum_belanja" value="{{ old('minimum_belanja', $promo->minimum_belanja ?? '') }}" placeholder="Contoh: 50000" min="0"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200">
                @error('minimum_belanja')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-brand-text-muted mb-1">Tanggal Mulai</label>
                {{-- PERBAIKAN: Pengecekan null pada tanggal sebelum format --}}
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $promo->tanggal_mulai ?? null ? \Carbon\Carbon::parse($promo->tanggal_mulai)->format('Y-m-d') : '') }}" placeholder="Pilih tanggal mulai"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200">
                @error('tanggal_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-brand-text-muted mb-1">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $promo->tanggal_selesai ?? null ? \Carbon\Carbon::parse($promo->tanggal_selesai)->format('Y-m-d') : '') }}" placeholder="Pilih tanggal selesai"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200">
                @error('tanggal_selesai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

             <div>
                <label for="deskripsi_promo" class="block text-sm font-medium text-brand-text-muted mb-1">Deskripsi</label>
                <textarea id="deskripsi_promo" name="deskripsi_promo" rows="4" placeholder="Deskripsi singkat mengenai promo ini..." class="w-full border-gray-300 rounded-lg shadow-sm resize-none focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition-colors duration-200">{{ old('deskripsi_promo', $promo->deskripsi_promo ?? '') }}</textarea>
                @error('deskripsi_promo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
        @if(isset($promo))
            <a href="{{ route('admin.promos.index') }}" class="text-sm text-brand-text-muted hover:underline">Batal</a>
        @endif
        <button type="submit" class="w-full sm:w-auto bg-brand-green hover:bg-brand-green-dark text-white font-bold px-8 py-2.5 rounded-lg shadow-md transition-transform hover:scale-105">
            {{ isset($promo) ? 'Update Promo' : 'Simpan Promo' }}
        </button>
    </div>
</form>