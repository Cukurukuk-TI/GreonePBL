@extends('layouts.admindashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-4 pt-14">Dashboard</h1>
    <p>Selamat datang di halaman dashboard admin.</p>

    <table class="table-fixed w-full border-collapse pt-4">
        <tr>
            <!-- Kolom 1-3 dilebarkan -->
            <td class=" w-[24%] p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20"></div>
            </td>
            <td class=" w-[24%] p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20"></div>
            </td>
            <td class=" w-[24%] p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20"></div>
            </td>
            <!-- Kolom 4+5 digabung dan dikurangi 1/5 ukuran (jadi 28%) -->
            <td class="   w-[28%] p-2" colspan="2" rowspan="2">
                <div class="bg-white shadow rounded-lg p-4 h-full min-h-[10rem]"></div>
            </td>
        </tr>
        <tr>
            <td class=" p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20"></div>
            </td>
            <td class=" p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20"></div>
            </td>
            <td class="  p-2">
                <div class="bg-white shadow rounded-lg p-4 h-20"></div>
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
    </script>
@endsection