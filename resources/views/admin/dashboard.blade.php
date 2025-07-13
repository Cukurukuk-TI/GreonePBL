@extends('layouts.admindashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-4 ">Dashboard</h1>
    <p>Selamat datang di halaman dashboard admin.</p>

    {{-- Baris statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Pendapatan --}}
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-money-bill-wave fa-lg text-green-600"></i></div>
            <div><p class="text-sm text-gray-500">Total Pendapatan</p><p class="text-xl font-bold text-gray-900">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p></div>
        </div>
        {{-- Total Pelanggan --}}
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"><i class="fas fa-users fa-lg text-blue-600"></i></div>
            <div><p class="text-sm text-gray-500">Total Pelanggan</p><p class="text-xl font-bold text-gray-900">{{ $stats['total_pelanggan'] ?? 0 }}</p></div>
        </div>
        {{-- Produk Saat Ini --}}
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center"><i class="fas fa-box-open fa-lg text-purple-600"></i></div>
            <div><p class="text-sm text-gray-500">Produk Saat Ini</p><p class="text-xl font-bold text-gray-900">{{ $stats['total_produk'] ?? 0 }}</p></div>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Pesanan --}}
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center"><i class="fas fa-receipt fa-lg text-indigo-600"></i></div>
            <div><p class="text-sm text-gray-500">Total Pesanan</p><p class="text-xl font-bold text-gray-900">{{ $stats['total_pesanan'] ?? 0 }}</p></div>
        </div>
        {{-- Pesanan Dikirim --}}
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center"><i class="fas fa-truck fa-lg text-yellow-600"></i></div>
            <div><p class="text-sm text-gray-500">Pesanan Dikirim</p><p class="text-xl font-bold text-gray-900">{{ $stats['pesanan_dikirim'] ?? 0 }}</p></div>
        </div>
        {{-- Pesanan Dibatalkan --}}
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-times-circle fa-lg text-red-600"></i></div>
            <div><p class="text-sm text-gray-500">Pesanan Dibatalkan</p><p class="text-xl font-bold text-gray-900">{{ $stats['pesanan_dibatalkan'] ?? 0 }}</p></div>
        </div>
    </div>

    <hr class="my-8 border-gray-200">

    {{-- GRAFIK BARIS PERTAMA (FILTER DINAMIS) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <div class="bg-white shadow p-4 rounded-lg">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-gray-800">Grafik Pendapatan</h3>
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <select id="pendapatan-periode-filter" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="monthly">Bulanan</option>
                            <option value="weekly">Mingguan</option>
                        </select>
                    </div>
                    <div class="relative">
                        <div id="pendapatan-month-container">
                            <select id="pendapatan-month-selector" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div id="pendapatan-week-container" class="hidden">
                            <select id="pendapatan-week-selector" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="current">Minggu Ini</option><option value="1">Minggu 1</option><option value="2">Minggu 2</option><option value="3">Minggu 3</option><option value="4">Minggu 4</option><option value="5">Minggu 5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative h-80"><canvas id="pendapatanChart"></canvas></div>
        </div>
        <div class="bg-white shadow p-4 rounded-lg">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-gray-800">Grafik Pesanan</h3>
                 <div class="flex items-center gap-2">
                    <div class="relative">
                        <select id="pesanan-periode-filter" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="monthly">Bulanan</option>
                            <option value="weekly">Mingguan</option>
                        </select>
                    </div>
                    <div class="relative">
                        <div id="pesanan-month-container">
                            <select id="pesanan-month-selector" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                 @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div id="pesanan-week-container" class="hidden">
                            <select id="pesanan-week-selector" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="current">Minggu Ini</option><option value="1">Minggu 1</option><option value="2">Minggu 2</option><option value="3">Minggu 3</option><option value="4">Minggu 4</option><option value="5">Minggu 5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative h-80"><canvas id="orderStatusChart"></canvas></div>
        </div>
    </div>

    <hr class="my-8 border-gray-200">

    {{-- Produk Paling Laris --}}
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <div><h2 class="text-xl font-bold text-gray-800">Produk Paling Laris</h2><p class="text-sm text-gray-500" id="periode-text-produk">{{ $currentPeriodName }}</p></div>
            
            {{-- Bagian filter dinonaktifkan sesuai permintaan --}}
            {{-- 
            <div class="flex items-center gap-2">
                <div class="relative">
                    <select id="produk-periode-filter" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="monthly">Bulanan</option>
                        <option value="weekly">Mingguan</option>
                    </select>
                </div>
                <div class="relative">
                    <div id="produk-month-container">
                        <select id="produk-month-selector" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                             @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div id="produk-week-container" class="hidden">
                        <select id="produk-week-selector" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="current">Minggu Ini</option><option value="1">Minggu 1</option><option value="2">Minggu 2</option><option value="3">Minggu 3</option><option value="4">Minggu 4</option><option value="5">Minggu 5</option>
                        </select>
                    </div>
                </div>
            </div>
            --}}
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex space-x-4 overflow-x-auto" id="produk-container">
                @forelse($produkTerlaris as $produk)
                    <div class="flex-shrink-0 w-48 bg-gray-50 rounded-lg p-4 produk-card">
                        <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                            @if($produk->gambar_produk)
                                <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><span class="text-gray-400 text-xs">No Image</span></div>
                            @endif
                        </div>
                        <h3 class="font-semibold text-sm text-gray-800 mb-1 truncate">{{ $produk->nama_produk }}</h3>
                        <p class="text-xs text-gray-500">{{ $produk->total_terjual }} item</p>
                    </div>
                @empty
                    <div class="w-full text-center py-8" id="no-produk-message"><p class="text-gray-500">Belum ada data produk terlaris.</p></div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let pendapatanChartInstance = null;
    let orderStatusChartInstance = null;
    
    // --- KONFIGURASI TOOLTIP ---
    const tooltipConfig = { mode: 'index', intersect: false };
    const tooltipLineCallback = { ...tooltipConfig, callbacks: { label: function(c) { return `${c.dataset.label}: Rp${c.parsed.y.toLocaleString('id-ID')}`; } } };

    // --- FUNGSI-FUNGSI UTAMA (LENGKAP) ---
    function renderChartError(canvasId, message) {
        const ctx = document.getElementById(canvasId)?.getContext('2d');
        if (!ctx) return;
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.font = '16px Arial'; ctx.fillStyle = '#ef4444'; ctx.textAlign = 'center';
        ctx.fillText(message, ctx.canvas.width / 2, ctx.canvas.height / 2);
    }
    function createLineChart(canvasId, chartData) {
        const ctx = document.getElementById(canvasId); if (!ctx) return null;
        if (!chartData || !chartData.labels || chartData.labels.length === 0) { renderChartError(canvasId, 'Tidak ada data.'); return null; }
        return new Chart(ctx, { type: 'line', data: { labels: chartData.labels, datasets: [{ label: 'Pendapatan (Rp)', data: chartData.pendapatan, borderColor: 'rgb(59, 130, 246)', backgroundColor: 'rgba(59, 130, 246, 0.2)', borderWidth: 2, tension: 0.3, fill: true }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true }, tooltip: tooltipLineCallback }, scales: { y: { beginAtZero: true, ticks: { callback: v => `Rp${v.toLocaleString('id-ID')}` } } } } });
    }
    function createBarChart(canvasId, chartData) {
        const ctx = document.getElementById(canvasId); if (!ctx) return null;
        if (!chartData || !chartData.labels || chartData.labels.length === 0) { renderChartError(canvasId, 'Tidak ada data.'); return null; }
        return new Chart(ctx, { type: 'bar', data: { labels: chartData.labels, datasets: [ { label: 'Total Pesanan', data: chartData.total_orders, backgroundColor: 'rgba(59, 130, 246, 0.8)' }, { label: 'Pesanan Selesai', data: chartData.completed_orders, backgroundColor: 'rgba(34, 197, 94, 0.8)' }, { label: 'Pesanan Dibatalkan', data: chartData.cancelled_orders, backgroundColor: 'rgba(239, 68, 68, 0.8)' } ] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true }, tooltip: tooltipConfig }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } } });
    }

    function updateDynamicChart(chartType) {
        const isPendapatan = chartType === 'pendapatan';
        const elements = {
            periodeFilter: document.getElementById(isPendapatan ? 'pendapatan-periode-filter' : 'pesanan-periode-filter'),
            monthSelector: document.getElementById(isPendapatan ? 'pendapatan-month-selector' : 'pesanan-month-selector'),
            weekSelector: document.getElementById(isPendapatan ? 'pendapatan-week-selector' : 'pesanan-week-selector'),
            endpoint: isPendapatan ? '/admin/grafik-pendapatan-ajax' : '/admin/grafik-pesanan-ajax',
            canvasId: isPendapatan ? 'pendapatanChart' : 'orderStatusChart',
        };
        let chartInstance = isPendapatan ? pendapatanChartInstance : orderStatusChartInstance;
        const createFunction = isPendapatan ? createLineChart : createBarChart;
        
        const periode = elements.periodeFilter.value;
        const timeUnit = (periode === 'monthly') ? elements.monthSelector.value : elements.weekSelector.value;
        const monthContext = elements.monthSelector.value;
        let url = `${elements.endpoint}?periode=${periode}&time_unit=${timeUnit}&month_context=${monthContext}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (chartInstance) chartInstance.destroy();
                const newChart = createFunction(elements.canvasId, data);
                if (isPendapatan) pendapatanChartInstance = newChart;
                else orderStatusChartInstance = newChart;
            })
            .catch(error => console.error(`Error fetching ${chartType} chart:`, error));
    }

    function fetchProdukTerlaris() {
        const produkContainer = document.getElementById('produk-container');
        const periodeText = document.getElementById('periode-text-produk');
        const periode = document.getElementById('produk-periode-filter').value;
        const monthSelector = document.getElementById('produk-month-selector');
        const weekSelector = document.getElementById('produk-week-selector');
        const timeUnit = (periode === 'monthly') ? monthSelector.value : weekSelector.value;
        const monthContext = monthSelector.value;

        produkContainer.innerHTML = '<div class="w-full flex justify-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-3xl"></i></div>';
        let url = `/admin/produk-terlaris-ajax?periode=${periode}&time_unit=${timeUnit}&month_context=${monthContext}`;
        
        console.log('[DEBUG-PRODUK] Fetching URL:', url); // <-- Tambahan log debug

        fetch(url).then(r => {
            if (!r.ok) throw new Error(`HTTP error! Status: ${r.status}`);
            return r.json();
        }).then(data => {
            console.log('[DEBUG-PRODUK] Data diterima:', data); // <-- Tambahan log debug
            let html = '';
            if (data.products && data.products.length > 0) {
                data.products.forEach(p => { html += `<div class="flex-shrink-0 w-48 bg-gray-50 rounded-lg p-4"><div class="w-full h-32 bg-gray-200 rounded-lg mb-3 overflow-hidden">${p.gambar_produk ? `<img src="{{ asset('storage/') }}/${p.gambar_produk}" alt="${p.nama_produk}" class="w-full h-full object-cover">` : '<div class="w-full h-full flex items-center justify-center"><span class="text-gray-400 text-xs">No Image</span></div>'}</div><h3 class="font-semibold text-sm text-gray-800 mb-1 truncate">${p.nama_produk}</h3><p class="text-xs text-gray-500">${p.total_terjual} item</p></div>`; });
            } else { html = '<div class="w-full text-center py-8"><p class="text-gray-500">Belum ada data produk untuk periode ini.</p></div>'; }
            produkContainer.innerHTML = html;
            if(data.currentPeriod) periodeText.textContent = data.currentPeriod;
        }).catch(e => {
            console.error('[DEBUG-PRODUK] Gagal fetch produk terlaris:', e);
            produkContainer.innerHTML = '<div class="w-full text-center py-8"><p class="text-red-500">Gagal memuat produk.</p></div>';
        });
    }

    // --- PENGATURAN EVENT LISTENERS ---
    function setupFilterListeners(prefix) {
        const periodeFilter = document.getElementById(`${prefix}-periode-filter`);
        const monthContainer = document.getElementById(`${prefix}-month-container`);
        const weekContainer = document.getElementById(`${prefix}-week-container`);
        const monthSelector = document.getElementById(`${prefix}-month-selector`);
        const weekSelector = document.getElementById(`${prefix}-week-selector`);
        
        // Cek jika elemen filter tidak ditemukan (karena sudah dicomment), maka hentikan fungsi.
        if (!periodeFilter) return;

        const isProduk = prefix === 'produk';
        const updateFunction = isProduk ? fetchProdukTerlaris : () => updateDynamicChart(prefix);

        periodeFilter.addEventListener('change', function() {
            const isMonthly = this.value === 'monthly';
            monthContainer.classList.toggle('hidden', !isMonthly);
            weekContainer.classList.toggle('hidden', isMonthly);
            updateFunction();
        });
        monthSelector.addEventListener('change', updateFunction);
        weekSelector.addEventListener('change', updateFunction);
    }
    
    setupFilterListeners('pendapatan');
    setupFilterListeners('pesanan');
    // setupFilterListeners('produk'); // <-- Listener untuk produk dinonaktifkan

    // --- INITIAL LOADS ---
    pendapatanChartInstance = createLineChart('pendapatanChart', @json($grafikDataPendapatan));
    orderStatusChartInstance = createBarChart('orderStatusChart', @json($grafikDataPesanan));
});
</script>
@endpush