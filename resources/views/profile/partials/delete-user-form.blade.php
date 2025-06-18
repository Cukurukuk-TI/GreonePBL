<section class="space-y-6">
    <header>
        <h3 class="text-lg font-medium text-gray-900">
            Hapus Akun
        </h3>
        <p class="mt-1 text-sm text-gray-600">
            Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Sebelum menghapus, harap unduh data apa pun yang ingin Anda simpan.
        </p>
    </header>

    {{-- Tombol untuk memicu modal --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-6 py-2 bg-red-600 text-white font-semibold rounded-md shadow hover:bg-red-700 text-sm"
    >Hapus Akun</button>

    {{-- Modal Konfirmasi --}}
    <div
        x-data="{ show: false }"
        x-show="show"
        x-on:open-modal.window="$event.detail === 'confirm-user-deletion' ? show = true : null"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div @click.away="show = false" class="bg-white p-8 rounded-lg shadow-xl max-w-lg w-full">
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus akun Anda?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                </p>

                <div>
                    <label for="password_delete" class="sr-only">Password</label>
                    <input
                        id="password_delete"
                        name="password"
                        type="password"
                        class="w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Password"
                        required
                    >
                    @error('password', 'userDeletion')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="show = false" class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>[x-cloak] { display: none !important; }</style>
