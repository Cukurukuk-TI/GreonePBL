<section>
    <header>
        <h3 class="text-lg font-medium text-gray-900">
            Ubah Password
        </h3>
        <p class="mt-1 text-sm text-gray-600">
            Pastikan Anda menggunakan password yang panjang dan acak agar tetap aman.
        </p>
    </header>

    @if (session('status') === 'password-updated')
        <div class="my-4 bg-green-100 border border-green-300 text-green-800 text-sm font-medium rounded-md p-4" role="alert">
            <p>Password Anda telah berhasil disimpan.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
            <input type="password" id="update_password_current_password" name="current_password" required autocomplete="current-password" class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            @error('current_password', 'updatePassword')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
            <input type="password" id="update_password_password" name="password" required autocomplete="new-password" class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            @error('password', 'updatePassword')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            @error('password_confirmation', 'updatePassword')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 text-sm">
                Simpan Password
            </button>
        </div>
    </form>
</section>
