@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 pt-14">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Pesanan Aktif</h1>
        <a href="{{ route('admin.pesanans.cancelled') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Lihat Pesanan Dibatalkan
        </a>
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

    <!-- Stats Card -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-orange-800">Menunggu</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $pesanans->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">Diproses</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $pesanans->where('status', 'proses')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">Dikirim</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $pesanans->where('status', 'dikirim')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">Selesai</p>
                    <p class="text-2xl font-bold text-green-900">{{ $pesanans->where('status', 'complete')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full border border-gray-300 text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">ID Pesanan</th>
                    <th class="border px-3 py-2">Nama Pelanggan</th>
                    <th class="border px-3 py-2">Nama Produk</th>
                    <th class="border px-3 py-2">Jumlah Pesanan</th>
                    <th class="border px-3 py-2">Tanggal Pesanan</th>
                    <th class="border px-3 py-2">Total Harga</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $pesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2 font-mono">{{ $pesanan->kode_pesanan }}</td>
                        <td class="border px-3 py-2">{{ $pesanan->user->name }}</td>
                        <td class="border px-3 py-2">{{ $pesanan->produk->nama_produk }}</td>
                        <td class="border px-3 py-2 text-center">{{ $pesanan->jumlah }}x</td>
                        <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="border px-3 py-2">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        <td class="border px-3 py-2">
                            @switch($pesanan->status)
                                @case('pending')
                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-semibold">Menunggu</span>
                                    @break
                                @case('proses')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">Di Proses</span>
                                    @break
                                @case('dikirim')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Di Kirim</span>
                                    @break
                                @case('complete')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Selesai</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="border px-3 py-2">
                            <form action="{{ route('admin.pesanans.update-status', $pesanan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="confirmStatusChange(this)" 
                                     class="text-xs border border-gray-300 rounded px-2 py-1 bg-white">
                                    <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="proses" {{ $pesanan->status == 'proses' ? 'selected' : '' }}>Di Proses</option>
                                    <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>Di Kirim</option>
                                    <option value="complete" {{ $pesanan->status == 'complete' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $pesanan->status == 'cancelled' ? 'selected' : '' }}>Batalkan</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-8">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4m-12 0H4m8 0V9m0 4v6m0-6L8 9m4 4l4-4"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada pesanan aktif</p>
                                <p class="text-sm text-gray-400">Pesanan baru akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination jika menggunakan paginate -->
    @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
        <div class="mt-4">
            {{ $pesanans->links() }}
        </div>
    @endif
</div>

<script>
function confirmStatusChange(selectElement) {
    const newStatus = selectElement.value;
    const currentStatus = selectElement.getAttribute('data-current-status') || selectElement.querySelector('option[selected]')?.value;
    
    let message = '';
    
    if (newStatus === 'cancelled') {
        message = 'Apakah Anda yakin ingin membatalkan pesanan ini? Pesanan akan dipindahkan ke daftar pesanan yang dibatalkan.';
    } else if (newStatus === 'complete') {
        message = 'Apakah Anda yakin pesanan ini sudah selesai?';
    } else {
        message = `Apakah Anda yakin ingin mengubah status menjadi ${newStatus}?`;
    }
    
    if (confirm(message)) {
        selectElement.form.submit();
    } else {
        // Reset ke status sebelumnya jika user cancel
        selectElement.value = currentStatus;
    }
}

// Set data attribute untuk current status
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="status"]');
    selects.forEach(select => {
        const selectedOption = select.querySelector('option[selected]');
        if (selectedOption) {
            select.setAttribute('data-current-status', selectedOption.value);
        }
    });
});
</script>
@endsection