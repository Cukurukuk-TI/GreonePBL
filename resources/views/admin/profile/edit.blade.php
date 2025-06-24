@extends('layouts.atminnav') {{-- Pastikan nama layout ini benar --}}

@section('title', 'Edit Profil')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 max-w-4xl mx-auto">

    <div class="text-left border-b border-gray-200 pb-4 mb-8">
        <h2 class="text-2xl font-bold text-brand-text">Edit Profil</h2>
        <p class="text-sm text-brand-text-muted mt-1">Perbarui informasi akun dan foto profil Anda di sini.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>Informasi profil Anda telah berhasil diperbarui.</p>
        </div>
    @endif

<form method="POST" action="{{route('admin.profile.update')}}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <h3 class="text-lg font-semibold text-brand-text">Foto Profil</h3>
                <p class="text-sm text-brand-text-muted mb-4">Gunakan foto terbaik Anda.</p>
                
                {{-- Komponen Upload Gambar Drag & Drop --}}
                <label id="drop-zone-foto" for="foto" class="mt-1 flex justify-center items-center w-full h-64 px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-brand-green transition-colors duration-200">
                    <div id="placeholder-foto" class="text-center">
                        <img id="preview-foto" 
                             src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : '' }}" 
                             alt="Preview" 
                             class="max-h-full max-w-full mx-auto rounded-md {{ auth()->user()->foto ? '' : 'hidden' }}">
                        
                        <div id="placeholder-icon" class="{{ auth()->user()->foto ? 'hidden' : '' }}">
                            <i class="fas fa-cloud-upload-alt fa-3x text-gray-400"></i>
                            <p class="mt-2 text-sm text-brand-text-muted"><span class="font-semibold">Drop gambar</span></p>
                            <p class="text-xs text-gray-500">atau klik untuk memilih</p>
                        </div>
                    </div>
                </label>
                <input type="file" id="foto" name="foto" class="hidden" accept="image/*">
                @error('foto')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-lg font-semibold text-brand-text">Informasi Pribadi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-brand-text-muted mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-brand-text-muted mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-brand-text-muted mb-1">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light">
                        @error('tanggal_lahir')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end items-center space-x-4 pt-6 mt-8 border-t border-gray-200">
            <a href="{{route('admin.profile.index')}}" class="text-sm font-medium text-brand-text-muted hover:underline"> {{-- href="{{ route('profile.show') }}" --}}
                Batal
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-green text-white font-bold rounded-lg shadow-md hover:bg-brand-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone-foto');
        const fileInput = document.getElementById('foto');
        const imagePreview = document.getElementById('preview-foto');
        const placeholder = document.getElementById('placeholder-icon');
        
        // Variable untuk mencegah event bubbling
        let isProcessing = false;

        // Event handler untuk click pada drop zone
        dropZone.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!isProcessing) {
                fileInput.click();
            }
        });

        // Drag and drop visual feedback
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!isProcessing) {
                dropZone.classList.add('border-brand-green', 'bg-green-50');
            }
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-brand-green', 'bg-green-50');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-brand-green', 'bg-green-50');
            
            if (!isProcessing && e.dataTransfer.files.length > 0) {
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    // Set file ke input
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                    
                    // Process file
                    handleFileSelect(file);
                }
            }
        });

        // Event handler untuk perubahan file input
        fileInput.addEventListener('change', function(e) {
            if (!isProcessing && e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (isProcessing) return;
            
            isProcessing = true;
            
            // Validasi file
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                resetFileInput();
                isProcessing = false;
                return;
            }
            
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB.');
                resetFileInput();
                isProcessing = false;
                return;
            }
            
            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                
                // Reset processing flag after preview loaded
                setTimeout(() => {
                    isProcessing = false;
                }, 100);
            };
            
            reader.onerror = function() {
                alert('Error reading file.');
                resetFileInput();
                isProcessing = false;
            };
            
            reader.readAsDataURL(file);
        }
        
        function resetFileInput() {
            fileInput.value = '';
            // Reset ke gambar asli jika ada
            const originalSrc = '{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : '' }}';
            if (originalSrc) {
                imagePreview.src = originalSrc;
                imagePreview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                imagePreview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    });
</script>
@endpush