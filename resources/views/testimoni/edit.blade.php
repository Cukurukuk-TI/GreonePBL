{{-- File ini akan dimuat sebagai modal/popup --}}
<div id="testimoniModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Edit Testimoni Anda</h3>
            <button onclick="closeTestimoniModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('testimoni.update', $testimoni->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Gunakan method PUT untuk update --}}

            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Ubah Rating:</label>
                <div class="flex space-x-1" id="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-8 h-8 text-gray-300 cursor-pointer star" data-rating="{{ $i }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path></svg>
                    @endfor
                    {{-- Nilai awal rating diambil dari data testimoni --}}
                    <input type="hidden" name="rating" id="rating-value" value="{{ old('rating', $testimoni->rating) }}" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Ubah Komentar:</label>
                {{-- Komentar lama ditampilkan di dalam textarea --}}
                <textarea name="komentar" id="komentar" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('komentar', $testimoni->komentar) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="foto_testimoni" class="block text-sm font-medium text-gray-700 mb-2">Ganti Foto (Opsional):</label>
                @if($testimoni->foto_testimoni)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $testimoni->foto_testimoni) }}" class="w-24 h-24 object-cover rounded-md border">
                    <p class="text-xs text-gray-500 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                </div>
                @endif
                <input type="file" name="foto_testimoni" id="foto_testimoni" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeTestimoniModal()" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
