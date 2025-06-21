@extends('layouts.admindashboard')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-brand-text">Daftar Pesanan Aktif</h1>
            <p class="text-sm text-brand-text-muted mt-1">Lacak dan kelola semua pesanan yang masuk.</p>
        </div>
        <a href="{{ route('admin.pesanans.cancelled') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg text-sm transition duration-200">
            <i class="fas fa-times-circle mr-2"></i>
            Lihat Pesanan Dibatalkan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="bg-orange-100 text-orange-500 p-3 rounded-full">
                <i class="fas fa-clock fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-brand-text-muted">Menunggu</p>
                <p class="text-2xl font-bold text-brand-text">{{ $pesanans->where('status', 'pending')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="bg-yellow-100 text-yellow-500 p-3 rounded-full">
                <i class="fas fa-cogs fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-brand-text-muted">Diproses</p>
                <p class="text-2xl font-bold text-brand-text">{{ $pesanans->where('status', 'proses')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="bg-blue-100 text-blue-500 p-3 rounded-full">
                <i class="fas fa-truck fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-brand-text-muted">Dikirim</p>
                <p class="text-2xl font-bold text-brand-text">{{ $pesanans->where('status', 'dikirim')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-md flex items-center gap-4">
            <div class="bg-green-100 text-green-500 p-3 rounded-full">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-brand-text-muted">Selesai</p>
                <p class="text-2xl font-bold text-brand-text">{{ $pesanans->where('status', 'complete')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold text-brand-text">Daftar Pesanan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs text-brand-text-muted uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">ID Pesanan</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Total Harga</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-brand-text font-mono">{{ $pesanan->kode_pesanan }}</p>
                                <p class="text-xs text-brand-text-muted">{{ $pesanan->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 font-medium text-brand-text">{{ $pesanan->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-brand-text-muted">
                                {{ $pesanan->produk->nama_produk ?? 'N/A' }}
                                <span class="font-semibold text-brand-text">({{ $pesanan->jumlah }}x)</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-brand-text">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-orange-100 text-orange-800',
                                        'proses' => 'bg-yellow-100 text-yellow-800',
                                        'dikirim' => 'bg-blue-100 text-blue-800',
                                        'complete' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$pesanan->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.pesanans.update-status', $pesanan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="confirmStatusChange(this, '{{ $pesanan->status }}')"
                                            class="text-xs font-semibold border-gray-300 rounded-md shadow-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green-light transition {{ $statusClasses[$pesanan->status] ?? 'bg-gray-100' }}">
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
                            <td colspan="6" class="text-center py-16 text-brand-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i>
                                    <span class="font-medium">Belum ada pesanan aktif.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($pesanans, 'hasPages') && $pesanans->hasPages())
            <div class="p-6 border-t">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmStatusChange(selectElement, originalStatus) {
    const newStatus = selectElement.value;
    if (newStatus === originalStatus) return;

    Swal.fire({
        title: 'Ubah Status Pesanan?',
        html: `Anda yakin ingin mengubah status dari "<b>${originalStatus}</b>" menjadi "<b>${newStatus}</b>"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#005E25',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Ubah Status!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            selectElement.form.submit();
        } else {
            selectElement.value = originalStatus;
        }
    });
}
</script>
@endpush