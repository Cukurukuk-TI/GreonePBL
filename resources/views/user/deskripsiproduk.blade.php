@extends('layouts.appnoslider')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <!-- Alert untuk pesan sukses -->
        @if(session('success'))
            <div id="successAlert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="closeAlert('successAlert')" class="absolute top-0 right-0 px-4 py-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Alert untuk pesan error -->
        @if(session('error'))
            <div id="errorAlert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 relative" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="closeAlert('errorAlert')" class="absolute top-0 right-0 px-4 py-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start test">
            <div id="gambar-produk" class="relative">
                <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}"
                    class="rounded-2xl border-4 border-blue-200 w-full object-cover max-h-[400px] cursor-pointer hover:opacity-90 transition-opacity" 
                    id="img-produk" onclick="openImageModal('{{ asset('storage/' . $produk->gambar_produk) }}', '{{ $produk->nama_produk }}')">
            </div>

            <div>
                <span class="inline-block px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full mb-2">
                    {{ $produk->kategori->nama_kategori }}
                </span>

                <h3 class="text-2xl font-bold">{{ $produk->nama_produk }}</h3>

                <p class="text-orange-500 text-2xl font-bold mt-2">
                    Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                </p>

                <div id="deskripsi-produk"
                    style="max-height: 200px; overflow-y: auto; margin-top: 1rem; padding-right: 0.5rem; border: 1px solid #ffffff;
                    border-radius: 0.5rem;">
                    <p class="text-gray-600 leading-relaxed whitespace-normal m-0 p-0">
                        {{ $produk->deskripsi_produk }}
                    </p>
                </div>

                <p class="text-sm text-gray-500 mt-2">Stok: <span class="font-semibold">{{ $produk->stok_produk }}</span>
                </p>

                <div class="mt-6 flex items-center gap-4">
                    <label class="font-semibold text-lg">Jumlah:</label>
                    <div class="flex items-center border rounded px-2 py-1 gap-2">
                        <button type="button" class="text-lg font-bold px-2" onclick="kurangiJumlah()">−</button>
                        <input id="jumlah" type="number" name="jumlah" value="1" min="1" max="{{ $produk->stok_produk }}"
                            class="w-12 text-center appearance-none border-none bg-transparent focus:outline-none focus:ring-0" />
                        <button type="button" class="text-lg font-bold px-2" onclick="tambahJumlah()">+</button>
                    </div>
                </div>
                <div class="mt-6">
                    <form method="POST" action="{{ route('keranjang.store') }}">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <input type="hidden" name="jumlah" id="jumlah-keranjang" value="1">
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <hr class="my-8">

        <h3 class="text-xl font-bold mb-4">Testimoni Pelanggan</h3>

        @if($produk->testimonis->isEmpty())
            <p class="text-gray-500 text-center py-8">Belum ada testimoni untuk produk ini.</p>
        @else
            <div class="space-y-6">
                @foreach($produk->testimonis as $testimoni)
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 relative">
                        <!-- Tombol hapus testimoni (hanya untuk user yang membuat testimoni) -->
                        @auth
                            @if(Auth::id() === $testimoni->user_id || Auth::user()->role === 'admin')
                                <form action="{{ route('testimoni.destroy', $testimoni->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus ulasan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full p-1 transition-all duration-200"
                                        title="Hapus testimoni">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth

                        <div class="flex items-center mb-2">
                            @if($testimoni->user->foto_profil)
                                <img src="{{ asset('storage/' . $testimoni->user->foto_profil) }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 mr-3">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="font-semibold text-gray-800">{{ $testimoni->user->name }}</div>
                        </div>

                        <div class="flex items-center mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $testimoni->rating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ $testimoni->rating }} / 5</span>
                        </div>

                        <p class="text-gray-700 mb-3">{{ $testimoni->komentar }}</p>

                        @if($testimoni->foto_testimoni)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $testimoni->foto_testimoni) }}" alt="Foto Testimoni" 
                                    class="w-[100px] h-[100px] rounded-lg shadow-md object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                    onclick="openImageModal('{{ asset('storage/' . $testimoni->foto_testimoni) }}', 'Foto Testimoni - {{ $testimoni->user->name }}')">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus Testimoni -->
    <div id="deleteTestimoniModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 shadow-lg">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Hapus Testimoni</h3>
                    <p class="text-sm text-gray-500 mt-1">Apakah Anda yakin ingin menghapus testimoni ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <form id="deleteTestimoniForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk preview gambar -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeImageModal()" 
                class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center hover:bg-opacity-75 transition-all z-10">
                ×
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded-lg">
            <div id="modalCaption" class="text-white text-center mt-2 text-lg"></div>
        </div>
    </div>

    <script>
        function tambahJumlah() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah-keranjang');
            const stokMaksimal = {{ $produk->stok_produk }};
            const currentValue = parseInt(input.value || 1);

            if (currentValue < stokMaksimal) {
                const newValue = currentValue + 1;
                input.value = newValue;
                hiddenInput.value = newValue;
            }
        }

        function kurangiJumlah() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah-keranjang');
            if (parseInt(input.value) > 1) {
                const newValue = parseInt(input.value) - 1;
                input.value = newValue;
                hiddenInput.value = newValue;
            }
        }

        // Sinkronisasi saat input berubah manual
        document.getElementById('jumlah').addEventListener('input', function() {
            const stokMaksimal = {{ $produk->stok_produk }};
            let value = parseInt(this.value) || 1;

            // Validasi batas minimum dan maksimum
            if (value < 1) value = 1;
            if (value > stokMaksimal) value = stokMaksimal;

            this.value = value;
            document.getElementById('jumlah-keranjang').value = value;
        });

        // Fungsi untuk modal preview gambar
        function openImageModal(imageSrc, caption) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalCaption = document.getElementById('modalCaption');
            
            modalImage.src = imageSrc;
            modalCaption.textContent = caption;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Disable scroll
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Enable scroll
        }

        // Close modal ketika klik di luar gambar
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closeDeleteModal();
            }
        });

        // Fungsi untuk modal hapus testimoni
        function confirmDeleteTestimoni(testimoniId) {
            const modal = document.getElementById('deleteTestimoniModal');
            const form = document.getElementById('deleteTestimoniForm');
            
            // Set action URL untuk form delete
            form.action = `/testimoni/${testimoniId}`;
            
            // Tampilkan modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteTestimoniModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal delete ketika klik di luar
        document.getElementById('deleteTestimoniModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Fungsi untuk menutup alert
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.display = 'none';
            }
        }

        // Auto-hide alert setelah 5 detik
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.style.display = 'none';
                    }, 300);
                }, 5000);
            }
            
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.opacity = '0';
                    setTimeout(function() {
                        errorAlert.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        });
    </script>
@endsection