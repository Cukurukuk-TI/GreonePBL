@extends('layouts.userlayouts')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 pt-16">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
        <div class="text-sm text-gray-500">
            Total: {{ $pesanans->count() }} pesanan
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'pending' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Menunggu
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'proses']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'proses' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Diproses
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'dikirim']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'dikirim' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Dikirim
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'complete']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'complete' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Selesai
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Dibatalkan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($pesanan->produk->gambar_produk)
                                            <img src="{{ asset('storage/' . $pesanan->produk->gambar_produk) }}" 
                                                 alt="{{ $pesanan->produk->nama_produk }}"
                                                 class="w-16 h-16 object-cover rounded-lg border">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg border flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">
                                            {{ $pesanan->produk->nama_produk }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Kode: {{ $pesanan->kode_pesanan }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Jumlah: {{ $pesanan->jumlah }}x
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $pesanan->metode_pengiriman === 'jemput' ? 'Jemput di Lokasi' : 'Diantar ke Alamat' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ ucfirst($pesanan->metode_pembayaran) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($pesanan->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Menunggu
                                        </span>
                                        @break
                                    @case('proses')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Diproses
                                        </span>
                                        @break
                                    @case('dikirim')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-.293-.707L15 4.586A1 1 0 0014.414 4H14v3z"></path>
                                            </svg>
                                            Dikirim
                                        </span>
                                        @break
                                    @case('complete')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Selesai
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Dibatalkan
                                        </span>
                                        @break
                                @endswitch
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    @if($pesanan->status === 'complete')
                                        @php
                                            $hasGivenTestimoni = \App\Models\Testimoni::where('user_id', Auth::id())
                                                                ->where('produk_id', $pesanan->produk_id)
                                                                ->exists();
                                        @endphp

                                        @if($hasGivenTestimoni)
                                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-xs font-medium rounded-md cursor-not-allowed">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Sudah Testimoni
                                            </span>
                                        @else
                                            <button type="button" onclick="showTestimoniModal('{{ route('testimoni.create', $pesanan->id) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Testimoni
                                            </button>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-xs font-medium rounded-md cursor-not-allowed">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Testimoni
                                        </span>
                                    @endif

                                    <button onclick="showOrderDetail('{{ $pesanan->id }}')" 
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6a2 2 0 002 2h4a2 2 0 002-2v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                                    <p class="text-gray-500 mb-4">Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                                    <a href="{{ route('home') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Mulai Belanja
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
        <div class="mt-6">
            {{ $pesanans->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<div id="testimoniModalContainer"></div>

<div id="orderDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
            <button onclick="closeOrderDetail()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="orderDetailContent">
            </div>
    </div>
</div>

<script>
// Fungsi untuk menampilkan detail pesanan
function showOrderDetail(orderId) {
    const modal = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailContent');
    
    content.innerHTML = `
        <div class="space-y-3">
            <div>
                <span class="font-medium">Kode Pesanan:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
            <div>
                <span class="font-medium">Metode Pengiriman:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
            <div>
                <span class="font-medium">Metode Pembayaran:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
            <div>
                <span class="font-medium">Alamat Pengiriman:</span>
                <span class="text-gray-600">Loading...</span>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Fungsi untuk menutup modal detail pesanan
function closeOrderDetail() {
    const modal = document.getElementById('orderDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Menutup modal detail pesanan ketika mengklik di luar area modal
document.getElementById('orderDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderDetail();
    }
});

// Fungsi inisialisasi bintang (akan dipanggil setelah modal testimoni dimuat)
function initializeStarRating() {
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingValueInput = document.getElementById('rating-value');

    stars.forEach(star => {
        // Hapus event listener lama jika ada, untuk mencegah duplikasi
        star.removeEventListener('click', starClickHandler);
        star.removeEventListener('mouseover', starMouseOverHandler);
        star.removeEventListener('mouseout', starMouseOutHandler);

        // Tambahkan event listener baru
        star.addEventListener('click', starClickHandler);
        star.addEventListener('mouseover', starMouseOverHandler);
        star.addEventListener('mouseout', starMouseOutHandler);
    });

    // Panggil sekali untuk memastikan tampilan awal sesuai ratingValueInput jika ada old value
    if (ratingValueInput.value > 0) {
        updateStarRating(ratingValueInput.value);
    }

    function starClickHandler() {
        const rating = this.dataset.rating;
        ratingValueInput.value = rating;
        updateStarRating(rating);
    }

    function starMouseOverHandler() {
        const rating = this.dataset.rating;
        highlightStars(rating);
    }

    function starMouseOutHandler() {
        updateStarRating(ratingValueInput.value);
    }

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
}

// Logic for Testimoni Modal
function showTestimoniModal(testimoniCreateUrl) {
    fetch(testimoniCreateUrl)
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Halaman testimoni tidak ditemukan. Periksa rute Anda.');
                }
                if (response.status === 403) {
                    throw new Error('Anda tidak memiliki izin untuk mengakses halaman ini.');
                }
                return response.text().then(text => { throw new Error(text) });
            }
            return response.text();
        })
        .then(html => {
            const container = document.getElementById('testimoniModalContainer');
            container.innerHTML = html;
            // Panggil fungsi inisialisasi bintang setelah modal dimuat
            initializeStarRating();

            // Tambahkan event listener untuk menutup modal ketika mengklik di luar area modal
            const testimoniModal = document.getElementById('testimoniModal');
            if (testimoniModal) {
                testimoniModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeTestimoniModal();
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading testimoni form:', error);
            alert('Gagal memuat formulir testimoni: ' + error.message);
        });
}

// Pastikan fungsi closeTestimoniModal tersedia secara global
function closeTestimoniModal() {
    const modal = document.getElementById('testimoniModal');
    if (modal) {
        modal.remove(); // Menghapus modal dari DOM
    }
}
</script>
@endsection