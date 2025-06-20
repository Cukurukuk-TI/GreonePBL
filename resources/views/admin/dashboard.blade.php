@extends('layouts.admindashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-4 pt-14">Dashboard</h1>
    <p>Selamat datang di halaman dashboard admin.</p>

     <table class="table-fixed w-full border-collapse pt-4">
        <tr>
            <!-- Total Pendapatan -->
            <td class="w-[24%] p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-500">Total Pendapatan</p>
                            <p class="text-lg font-bold text-gray-900">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </td>
            
            <!-- Total Pelanggan -->
            <td class="w-[24%] p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-500">Total Pelanggan</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['total_pelanggan'] }}</p>
                        </div>
                    </div>
                </div>
            </td>
            
            <!-- Total Produk -->
            <td class="w-[24%] p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-500">Produk Saat Ini</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['total_produk'] }}</p>
                        </div>
                    </div>
                </div>
            </td>
            
            <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="bg-white shadow p-4 rounded-lg">
                <h3 class="text-sm font-semibold mb-2 text-gray-700">Grafik Pendapatan Harian</h3>
                <canvas id="pendapatanChart"></canvas>
            </div>
            <div class="bg-white shadow p-4 rounded-lg">
                <h3 class="text-sm font-semibold mb-2 text-gray-700">Grafik Jumlah Pesanan Harian</h3>
                <canvas id="pesananChart"></canvas>
            </div>
        </div>
        </tr>

            <!-- Pesanan Dikirim -->
            <td class="p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-500">Pesanan Dikirim</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['pesanan_dikirim'] }}</p>
                        </div>
                    </div>
                </div>
            </td>
            
            <!-- Pesanan Dibatalkan -->
            <td class="p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-500">Pesanan Dibatalkan</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['pesanan_dibatalkan'] }}</p>
                        </div>
                    </div>
                </div>
            </td>
            
            <!-- Total Pesanan -->
            <td class="p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-500">Total Pesanan</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['total_pesanan'] }}</p>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>


    <!-- Produk Terlaris Section -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Produk Paling Laris</h2>
                <p class="text-sm text-gray-500" id="periode-text">
                    @if(isset($currentMonth))
                        {{ $currentMonth }}
                    @else
                        Bulan sekarang
                    @endif
                </p>
            </div>
            <div class="relative">
                <select id="periode-filter" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="monthly">Bulanan</option>
                    <option value="weekly">Mingguan</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex space-x-4 overflow-x-auto" id="produk-container">
                @if(isset($produkTerlaris) && $produkTerlaris->count() > 0)
                    @foreach($produkTerlaris as $produk)
                        <div class="flex-shrink-0 w-48 bg-gray-50 rounded-lg p-4 produk-card" data-periode="monthly">
                            <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                                @if($produk->gambar_produk)
                                    <img src="{{ asset('storage/' . $produk->gambar_produk) }}" 
                                         alt="{{ $produk->nama_produk }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-semibold text-sm text-gray-800 mb-1 truncate">{{ $produk->nama_produk }}</h3>
                            <p class="text-xs text-gray-500">{{ $produk->total_terjual }} item</p>
                        </div>
                    @endforeach
                @else
                    <div class="w-full text-center py-8">
                        <p class="text-gray-500">Belum ada data produk terlaris</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodeFilter = document.getElementById('periode-filter');
            const periodeText = document.getElementById('periode-text');
            
            // Fungsi untuk mengkonversi nomor bulan ke nama bulan Indonesia
            function getMonthName(monthNumber) {
                const months = {
                    1: 'Januari',
                    2: 'Februari', 
                    3: 'Maret',
                    4: 'April',
                    5: 'Mei',
                    6: 'Juni',
                    7: 'Juli',
                    8: 'Agustus',
                    9: 'September',
                    10: 'Oktober',
                    11: 'November',
                    12: 'Desember'
                };
                return months[monthNumber] || 'Bulan tidak valid';
            }
            
            periodeFilter.addEventListener('change', function() {
                const selectedValue = this.value;
                
                if (selectedValue === 'monthly') {
                    // Ambil bulan dari backend atau gunakan bulan saat ini
                    @if(isset($currentMonth))
                        periodeText.textContent = '{{ $currentMonth }}';
                    @else
                        const currentMonth = new Date().getMonth() + 1;
                        periodeText.textContent = getMonthName(currentMonth);
                    @endif
                } else if (selectedValue === 'weekly') {
                    periodeText.textContent = 'Minggu sekarang';
                }
                
                // AJAX call untuk mengambil data baru berdasarkan filter
                fetchProdukTerlaris(selectedValue);
            });
            
            function fetchProdukTerlaris(periode) {
                console.log('Fetching data for periode:', periode);
                
                // Implementasi AJAX untuk mengambil data produk terlaris
                fetch(`/admin/produk-terlaris?periode=${periode}`)
                    .then(response => response.json())
                    .then(data => {
                        updateProdukContainer(data);
                        
                        // Update text periode berdasarkan response
                        if (data.currentPeriod) {
                            periodeText.textContent = data.currentPeriod;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
            
            function updateProdukContainer(data) {
                const container = document.getElementById('produk-container');
                
                if (data.products && data.products.length > 0) {
                    let html = '';
                    data.products.forEach(produk => {
                        html += `
                            <div class="flex-shrink-0 w-48 bg-gray-50 rounded-lg p-4 produk-card">
                                <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                                    ${produk.gambar_produk ? 
                                        `<img src="/storage/${produk.gambar_produk}" alt="${produk.nama_produk}" class="w-full h-full object-cover">` :
                                        '<div class="w-full h-full flex items-center justify-center"><span class="text-gray-400 text-xs">No Image</span></div>'
                                    }
                                </div>
                                <h3 class="font-semibold text-sm text-gray-800 mb-1 truncate">${produk.nama_produk}</h3>
                                <p class="text-xs text-gray-500">${produk.total_terjual} item</p>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="w-full text-center py-8"><p class="text-gray-500">Belum ada data produk terlaris</p></div>';
                }
            }
        });

        // Inisialisasi grafik pendapatan harian
        document.addEventListener('DOMContentLoaded', function () {
    if (!window.dailyStats) return;

    const labels = dailyStats.map(item => item.tanggal);
    const pendapatanData = dailyStats.map(item => item.pendapatan);
    const pesananData = dailyStats.map(item => item.jumlah_pesanan);

    const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
    const ctxPesanan = document.getElementById('pesananChart').getContext('2d');

    new Chart(ctxPendapatan, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rp',
                data: pendapatanData,
                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                borderColor: 'rgba(5, 150, 105, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp' + value.toLocaleString()
                    }
                }
            }
        }
    });

    new Chart(ctxPesanan, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: pesananData,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

    </script>
    <script src="/path/to/chart.js"></script>
@endsection
