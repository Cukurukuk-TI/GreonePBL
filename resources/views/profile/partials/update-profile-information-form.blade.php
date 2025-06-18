<section>
    <header>
        <h3 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h3>
        <p class="mt-1 text-sm text-gray-600">
            Perbarui data profil dan alamat email akun Anda.
        </p>
    </header>

    @if (session('status') === 'profile-updated')
        <div class="my-4 bg-green-100 border border-green-300 text-green-800 text-sm font-medium rounded-md p-4" role="alert">
            <p>Profil Anda telah berhasil diperbarui.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6" x-data="fileUpload()">
        @csrf
        @method('patch')

        {{-- Nama Lengkap --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus class="w-full border-gray-300 rounded-md shadow-sm">
            @error('name', 'updateProfileInformation')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
            @error('email', 'updateProfileInformation')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-md p-3">
                    Alamat email Anda belum terverifikasi.
                    <button form="send-verification" class="underline font-semibold hover:text-yellow-900 ml-1">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                </div>
            @endif
        </div>

        {{-- Jenis Kelamin --}}
        <div>
            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        {{-- Tanggal Lahir --}}
        <div>
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" class="w-full border-gray-300 rounded-md shadow-sm">
        </div>

        {{-- Foto Profil --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
            <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="isDragging = false; handleDrop($event)"
                 :class="{'border-blue-500 bg-blue-50': isDragging}" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="foto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                            <span>Unggah file</span>
                            <input id="foto" name="foto" type="file" class="sr-only" accept="image/*" @change="handleFileSelect($event)">
                        </label>
                        <p class="pl-1">atau seret dan lepas</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                </div>
            </div>

            {{-- Preview Area --}}
            <div x-show="previewUrl" class="mt-4">
                <div class="p-3 border rounded-md bg-gray-50 flex items-center space-x-4">
                    <img :src="previewUrl" class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                    <div class="text-sm flex-1">
                        <p class="font-medium text-gray-800" x-text="fileName"></p>
                        <p class="text-gray-500" x-text="fileSize"></p>
                    </div>
                    <button @click="removePreview()" type="button" class="text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
                </div>
            </div>

            @error('foto', 'updateProfileInformation')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</section>

{{-- Form tersembunyi untuk verifikasi --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">@csrf</form>

{{-- Script untuk Preview Foto - Diperbaiki untuk mencegah duplikasi --}}
<script>
    function fileUpload() {
        return {
            isDragging: false,
            previewUrl: '',
            fileName: '',
            fileSize: '',

            handleDrop(event) {
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    this.updatePreview(files);
                }
            },

            handleFileSelect(event) {
                const files = event.target.files;
                if (files.length > 0) {
                    this.updatePreview(files);
                }
            },

            updatePreview(files) {
                // Bersihkan preview sebelumnya jika ada
                this.clearPreview();

                const file = files[0];

                // Validasi tipe file
                if (!file.type.startsWith('image/')) {
                    alert('Harap pilih file gambar (PNG, JPG, GIF)');
                    return;
                }

                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file tidak boleh lebih dari 2MB');
                    return;
                }

                // Update file input
                const fileInput = document.getElementById('foto');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                // Set preview data
                this.fileName = file.name;
                this.fileSize = this.formatFileSize(file.size);
                this.previewUrl = URL.createObjectURL(file);
            },

            removePreview() {
                this.clearPreview();
                // Reset file input
                const fileInput = document.getElementById('foto');
                fileInput.value = '';
            },

            clearPreview() {
                // Revoke previous object URL untuk mencegah memory leak
                if (this.previewUrl) {
                    URL.revokeObjectURL(this.previewUrl);
                }
                this.previewUrl = '';
                this.fileName = '';
                this.fileSize = '';
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        }
    }
</script>
