<div id="testimoniModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Berikan Testimoni Anda</h3>
            <button onclick="closeTestimoniModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('testimoni.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">
            {{-- FIX: Mengambil produk id dari objek $detail --}}
            <input type="hidden" name="produk_id" value="{{ $detail->produk->id }}">

            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 mr-4">
                    {{-- FIX: Menggunakan $detail->produk untuk info produk --}}
                    @if($detail->produk->gambar_produk)
                        <img src="{{ asset('storage/' . $detail->produk->gambar_produk) }}" alt="{{ $detail->produk->nama_produk }}" class="w-20 h-20 object-cover rounded-lg border">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg border flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div>
                    {{-- FIX: Menggunakan $detail->produk untuk nama produk --}}
                    <h4 class="text-md font-semibold text-gray-800">{{ $detail->produk->nama_produk }}</h4>
                    {{-- FIX: Mengambil jumlah dari objek $detail --}}
                    <p class="text-sm text-gray-500">Jumlah: {{ $detail->jumlah }}x</p>
                </div>
            </div>

            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating Kualitas Produk:</label>
                <div class="flex space-x-1" id="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-8 h-8 text-gray-300 cursor-pointer star" data-rating="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                    <input type="hidden" name="rating" id="rating-value" value="0" required>
                </div>
                @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Komentar Anda:</label>
                <textarea name="komentar" id="komentar" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Berikan pendapat Anda tentang produk ini..." required>{{ old('komentar') }}</textarea>
                @error('komentar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="foto_testimoni" class="block text-sm font-medium text-gray-700 mb-2">Foto (boleh kosong):</label>
                <input type="file" name="foto_testimoni" id="foto_testimoni" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                @error('foto_testimoni')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeTestimoniModal()" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Kirim Testimoni</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('#star-rating .star');
        const ratingValueInput = document.getElementById('rating-value');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingValueInput.value = rating;
                updateStarRating(rating);
            });

            star.addEventListener('mouseover', function() {
                const rating = this.dataset.rating;
                highlightStars(rating);
            });

            star.addEventListener('mouseout', function() {
                updateStarRating(ratingValueInput.value);
            });
        });

        function updateStarRating(rating) {
            stars.forEach(star => {
                if (star.dataset.rating <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        function highlightStars(rating) {
            stars.forEach(star => {
                if (star.dataset.rating <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-300'); // Warna saat hover
                } else {
                    star.classList.remove('text-yellow-300');
                    star.classList.add('text-gray-300');
                }
            });
        }

        if (ratingValueInput.value > 0) {
            updateStarRating(ratingValueInput.value);
        }
    });

    function closeTestimoniModal() {
        document.getElementById('testimoniModal').remove();
    }
</script>