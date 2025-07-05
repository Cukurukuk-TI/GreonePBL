@extends('layouts.userlayouts')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 pt-16">
    {{-- Header Halaman --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
        <div class="text-sm text-gray-500">
            Total: {{ $pesanans->total() }} pesanan
        </div>
    </div>

    {{-- Notifikasi Sukses/Error --}}
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

    {{-- Filter Status Pesanan --}}
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'pending' ? 'bg-orange-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Menunggu
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'proses']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'proses' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Diproses
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'dikirim']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'dikirim' ? 'bg-cyan-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Dikirim
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'complete']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'complete' ? 'bg-green-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Selesai
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'cancelled' ? 'bg-red-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Dibatalkan
            </a>
        </div>
    </div>

    {{-- Tabel Daftar Pesanan --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pesanans as $pesanan)
                        @php
                            $firstDetail = $pesanan->details->first();
                            $produk = $firstDetail ? $firstDetail->produk : null;
                            
                            // Cek apakah sudah ada testimoni untuk pesanan ini
                            $produkIds = $pesanan->details->pluck('produk_id');
                            $hasTestimoni = App\Models\Testimoni::where('user_id', auth()->id())
                                                ->whereIn('produk_id', $produkIds)
                                                ->exists();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($produk && $produk->gambar_produk)
                                            <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}" class="w-16 h-16 object-cover rounded-lg border">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg border flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">
                                            @if($produk)
                                                {{ $produk->nama_produk }}
                                                @if($pesanan->details->count() > 1)
                                                    <span class="text-gray-500 text-xs">(+{{ $pesanan->details->count() - 1 }} item)</span>
                                                @endif
                                            @else
                                                Produk tidak ditemukan
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">Kode: {{ $pesanan->kode_pesanan }}</div>
                                        <div class="text-sm text-gray-500">
                                            Total Item: {{ $pesanan->details->sum('jumlah') }}x
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $pesanan->metode_pengiriman === 'jemput' ? 'Jemput di Lokasi' : 'Diantar ke Alamat' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($pesanan->metode_pembayaran) }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-orange-100 text-orange-800',
                                        'proses' => 'bg-yellow-100 text-yellow-800',
                                        'dikirim' => 'bg-cyan-100 text-cyan-800',
                                        'complete' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ][$pesanan->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col items-stretch space-y-2">
                                    <button onclick="showOrderDetail('{{ $pesanan->id }}')" class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition duration-200">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>
                                    
                                    {{-- TOMBOL BAYAR SEKARANG UNTUK STATUS UNPAID --}}
                                    @if ($pesanan->status == 'unpaid' && $pesanan->metode_pembayaran == 'transfer')
                                        <a href="{{ route('pesanan.payment', $pesanan->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition duration-200">
                                            <i class="fas fa-money-bill-wave mr-1"></i> Bayar
                                        </a>
                                    @endif

                                    {{-- Logika Pembatalan: bisa batal jika 'unpaid' atau 'pending' --}}
                                    @if (in_array($pesanan->status, ['unpaid', 'pending']))
                                        <form action="{{ route('pesanan.user.cancel', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition duration-200">
                                                <i class="fas fa-times mr-1"></i> Batal
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Tombol Testimoni untuk pesanan yang sudah selesai --}}
                                    @if($pesanan->status === 'complete')
                                        @if($hasTestimoni)
                                            <button disabled class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-gray-400 text-white text-xs font-medium rounded-md cursor-not-allowed opacity-60">
                                                <i class="fas fa-check mr-1"></i> Sudah Ditestimoni
                                            </button>
                                        @else
                                            <button onclick="showTestimoniModal('{{ route('testimoni.create', ['pesanan_id' => $pesanan->id]) }}')" class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-md transition duration-200">
                                                <i class="fas fa-star mr-1"></i> Beri Testimoni
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                                    <p class="text-gray-500 mb-4">Anda belum memiliki riwayat pesanan.</p>
                                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200">
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

    {{-- Pagination --}}
    @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
        <div class="mt-6">
            {{ $pesanans->appends(request()->query())->links() }}
        </div>
    @endif
</div>

{{-- Placeholder untuk Modal Testimoni & Detail --}}
<div id="testimoniModalContainer"></div>

<div id="orderDetailModal"
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4 transition-opacity duration-300"
     onclick="closeOrderDetail(event)">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-transform duration-300 scale-95 relative"
         onclick="event.stopPropagation()">

        {{-- Tombol Close di pojok kanan atas --}}
        <button onclick="closeOrderDetail(event)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
             <i class="fas fa-times text-2xl"></i>
        </button>

        {{-- Konten Modal akan diisi oleh JavaScript --}}
        <div id="orderDetailContent" class="p-6 md:p-8">
            </div>
    </div>
</div>

<script>
// Objek untuk info status (badge, warna, dll)
const statusInfo = {
    pending: { text: 'Menunggu', class: 'bg-orange-100 text-orange-800' },
    proses: { text: 'Diproses', class: 'bg-yellow-100 text-yellow-800' },
    dikirim: { text: 'Dikirim', class: 'bg-cyan-100 text-cyan-800' },
    complete: { text: 'Selesai', class: 'bg-green-100 text-green-800' },
    cancelled: { text: 'Dibatalkan', class: 'bg-red-100 text-red-800' }
};

// Fungsi untuk menutup modal dengan animasi
function closeOrderDetail(event) {
    if (event) {
        event.stopPropagation();
    }
    const modal = document.getElementById('orderDetailModal');
    const modalInner = modal.querySelector('div[onclick="event.stopPropagation()"]'); // Target div dalam

    // Animasi keluar
    modalInner.classList.remove('scale-100');
    modalInner.classList.add('scale-95');
    modal.classList.add('opacity-0');

    // Tunggu animasi selesai sebelum menyembunyikan modal
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Fungsi UTAMA untuk menampilkan detail pesanan dengan desain baru
async function showOrderDetail(orderId) {
    const modal = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailContent');
    const modalInner = modal.querySelector('div[onclick="event.stopPropagation()"]');
    const url = `{{ url('/pesanan-detail') }}/${orderId}`;

    // Tampilkan modal dengan status loading dan animasi masuk
    modal.classList.remove('hidden', 'opacity-0');
    setTimeout(() => {
        modalInner.classList.remove('scale-95');
        modalInner.classList.add('scale-100');
    }, 10);
    content.innerHTML = '<div class="text-center py-20"><i class="fas fa-spinner fa-spin fa-2x text-gray-400"></i><p class="mt-2 text-gray-500">Memuat detail...</p></div>';

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`Gagal mengambil data, status: ${response.status}`);

        const result = await response.json();
        if (result.success) {
            const pesanan = result.data;
            const status = statusInfo[pesanan.status] || { text: 'Tidak Diketahui', class: 'bg-gray-100 text-gray-800' };

            // Membuat daftar item produk
            let itemsHtml = pesanan.items.map(item => `
                <div class="flex items-center space-x-4 py-3">
                    <img src="${item.gambar_url}" alt="${item.nama_produk}" class="w-16 h-16 object-cover rounded-md border">
                    <div class="flex-grow">
                        <p class="font-semibold text-gray-800">${item.nama_produk}</p>
                        <p class="text-sm text-gray-500">${item.jumlah} x ${item.harga_satuan}</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">${item.subtotal}</p>
                </div>
            `).join('');

            // Merakit seluruh HTML untuk konten modal dengan desain baru
            content.innerHTML = `
                <div class="space-y-6">
                    <div class="text-center border-b pb-4">
                        <p class="text-sm text-gray-500">${pesanan.tanggal}</p>
                        <h4 class="text-2xl font-bold text-gray-900 mt-1">${pesanan.kode_pesanan}</h4>
                        <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${status.class}">${status.text}</span>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-800 mb-2">Rincian Produk</h5>
                        <div class="border-t border-b divide-y max-h-60 overflow-y-auto pr-2">
                            ${itemsHtml}
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-medium text-gray-900">${pesanan.subtotal}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Ongkos Kirim</span><span class="font-medium text-gray-900">${pesanan.ongkos_kirim}</span></div>
                        <div class="flex justify-between text-green-600"><span class="font-medium">Diskon</span><span class="font-medium">${pesanan.diskon}</span></div>
                        <div class="flex justify-between text-base font-bold text-gray-900 border-t pt-2 mt-2"><span class="">Total Pembayaran</span><span>${pesanan.total_harga}</span></div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <h5 class="font-semibold text-gray-800 mb-2">Info Pengiriman & Pembayaran</h5>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Metode Kirim:</strong> ${pesanan.metode_pengiriman === 'jemput' ? 'Jemput di Lokasi' : 'Diantar'}</p>
                            <p><strong>Metode Bayar:</strong> ${pesanan.metode_pembayaran.toUpperCase()}</p>
                            <p><strong>Alamat:</strong> ${pesanan.alamat_pengiriman}</p>
                            <p><strong>Catatan:</strong> ${pesanan.catatan}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            throw new Error(result.error || 'Data tidak ditemukan.');
        }
    } catch (error) {
        console.error('Error fetching order details:', error);
        content.innerHTML = `<div class="text-center py-10"><i class="fas fa-exclamation-triangle text-red-500 fa-2x"></i><p class="mt-2 text-red-600">Gagal memuat detail pesanan.</p><p class="text-xs text-gray-500 mt-1">${error.message}</p></div>`;
    }
}

// Event listener untuk menutup modal saat menekan tombol Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeOrderDetail();
    }
});

// --- Kode untuk Testimoni ---
function initializeStarRating() {
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingValueInput = document.getElementById('rating-value');
    if(!stars.length || !ratingValueInput) return; // guard clause

    stars.forEach(star => {
        star.addEventListener('click', function() {
            ratingValueInput.value = this.dataset.rating;
            updateStarAppearance(this.dataset.rating);
        });
    });

    function updateStarAppearance(rating) {
        stars.forEach(s => {
            s.classList.toggle('text-yellow-400', s.dataset.rating <= rating);
            s.classList.toggle('text-gray-300', s.dataset.rating > rating);
        });
    }
}

function showTestimoniModal(testimoniCreateUrl) {
    fetch(testimoniCreateUrl)
        .then(response => {
            if (!response.ok) throw new Error('Gagal memuat form testimoni.');
            return response.text();
        })
        .then(html => {
            document.getElementById('testimoniModalContainer').innerHTML = html;
            const modalElement = document.getElementById('testimoniModal');
            if (modalElement) {
                // Inisialisasi bintang setelah modal dimuat
                initializeStarRating();
                // Event listener untuk menutup modal
                modalElement.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeTestimoniModal();
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Tampilkan pesan error di modal container
            document.getElementById('testimoniModalContainer').innerHTML = `
                <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
                            <p class="text-gray-700 mb-4">Gagal memuat form testimoni</p>
                            <button onclick="closeTestimoniModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
}

function closeTestimoniModal() {
    const modalContainer = document.getElementById('testimoniModalContainer');
    if (modalContainer) {
        modalContainer.innerHTML = ''; // Kosongkan container
    }
}

// Fungsi untuk reload halaman setelah testimoni berhasil ditambahkan
function reloadAfterTestimoni() {
    closeTestimoniModal();
    setTimeout(() => {
        location.reload();
    }, 500);
}
</script>
@endsection