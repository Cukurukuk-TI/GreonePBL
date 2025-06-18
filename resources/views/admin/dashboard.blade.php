{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

{{-- Judul ini akan otomatis muncul di header --}}
@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        
        <div class="bg-white p-5 rounded-lg shadow-md flex items-center">
            <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">
                <i class="fas fa-dollar-sign fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500">Total Pendapatan</p>
                <h4 class="text-2xl font-bold text-slate-800">Rp270.000</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-md flex items-center">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500">Total Pelanggan</p>
                <h4 class="text-2xl font-bold text-slate-800">90</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-md flex items-center">
            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mr-4">
                <i class="fas fa-box-open fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500">Produk Saat Ini</p>
                <h4 class="text-2xl font-bold text-slate-800">30</h4>
            </div>
        </div>

         <div class="bg-white p-5 rounded-lg shadow-md flex items-center">
            <div class="bg-purple-100 text-purple-600 p-3 rounded-full mr-4">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500">Total Pesanan</p>
                <h4 class="text-2xl font-bold text-slate-800">3</h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h5 class="text-lg font-bold mb-4">Statistik Pendapatan</h5>
            <div style="height: 300px;">
                {{-- Canvas untuk Chart.js bisa diletakkan di sini --}}
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h5 class="text-lg font-bold mb-4">Produk Paling Laris</h5>
            <div class="space-y-4">
                {{-- Looping produk terlaris di sini --}}
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-slate-200 rounded-md mr-4 flex-shrink-0"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Pakcoy Slebew</p>
                        <p class="text-sm text-slate-500">2 item terjual</p>
                    </div>
                </div>
                 <div class="flex items-center">
                    <div class="w-12 h-12 bg-slate-200 rounded-md mr-4 flex-shrink-0"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Pakcoy Slebew</p>
                        <p class="text-sm text-slate-500">2 item terjual</p>
                    </div>
                </div>
                </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Script untuk Chart.js (sama seperti sebelumnya)
    const ctx = document.getElementById('incomeChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Pendapatan',
                    data: [120, 190, 300, 500, 220, 310],
                    backgroundColor: 'rgba(22, 163, 74, 0.2)',
                    borderColor: 'rgba(22, 163, 74, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
</script>
@endpush