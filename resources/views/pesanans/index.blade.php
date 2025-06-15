@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 pt-14">
    <h1 class="text-2xl font-bold mb-6">Daftar Pesanan</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
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
                        <td class="border px-3 py-2">{{ $pesanan->tanggal_pesanan }}</td>
                        <td class="border px-3 py-2">{{ $pesanan->formatted_total_harga }}</td>
                        <td class="border px-3 py-2">
                            @if($pesanan->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">Pending</span>
                            @elseif($pesanan->status == 'proses')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Proses</span>
                            @else
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Complete</span>
                            @endif
                        </td>
                        <td class="border px-3 py-2">
                            <form action="{{ route('admin.pesanans.update-status', $pesanan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                     class="text-xs border border-gray-300 rounded px-2 py-1">
                                    <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="proses" {{ $pesanan->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="complete" {{ $pesanan->status == 'complete' ? 'selected' : '' }}>Complete</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection