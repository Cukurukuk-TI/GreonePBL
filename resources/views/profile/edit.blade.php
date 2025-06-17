@extends('layouts.profileuser')

@section('profile-content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-3xl mx-auto mt-6">
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
        <p class="text-sm text-gray-500 mt-1">Perbarui informasi akunmu di bawah ini</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Gender -->
        <div>
            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin"
                class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Birth Date -->
        <div>
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir) }}"
                class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
            @error('tanggal_lahir')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Foto Profil -->
        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (baru)</label>
            <input type="file" id="foto" name="foto"
                class="block w-full text-sm text-gray-600 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0 file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
            @error('foto')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end items-center space-x-4 pt-6">
            <!-- Tombol Kembali -->
            <a href="{{ route('profile.content') }}"
               class="inline-flex items-center px-4 py-2 border border-green-600 text-green-600 font-semibold rounded-md hover:bg-green-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>

            <!-- Tombol Simpan -->
            <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fotoInput = document.getElementById('foto');
        fotoInput.addEventListener('change', function () {
            if (fotoInput.files && fotoInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewImage = document.getElementById('preview-image');
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                };
                reader.readAsDataURL(fotoInput.files[0]);
            }
        });
    });
</script>
@endsection
