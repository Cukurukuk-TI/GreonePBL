@extends('layouts.profileuser')

@section('profile-content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-3xl mx-auto mt-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
        <p class="text-sm text-gray-500 mt-1">Perbarui informasi akunmu di bawah ini</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="mb-6 bg-green-100 border border-green-300 text-green-800 text-sm font-medium rounded-md p-4" role="alert">
            <p>Profil Anda telah berhasil diperbarui.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus
                   class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">

            <p class="text-xs text-gray-500 mt-1">
                Jika Anda mengubah alamat email, Anda akan diminta untuk melakukan verifikasi ulang pada email baru Anda.
            </p>

            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-md p-3">
                    Alamat email Anda belum terverifikasi.
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="underline font-semibold hover:text-yellow-900 ml-1">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </form>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                   class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
            @error('tanggal_lahir')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

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

        <div class="flex justify-end items-center space-x-4 pt-6">
            <a href="{{ route('profile.content') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-400 text-gray-700 font-semibold rounded-md hover:bg-gray-100 transition-colors text-sm">
                Kembali
            </a>

            <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all text-sm">
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
                    if(previewImage) {
                       previewImage.src = e.target.result;
                       previewImage.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(fotoInput.files[0]);
            }
        });
    });
</script>
@endsection
