@extends('layouts.admindashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Manajemen Pelanggan</h1>
    <p class="mb-4">Daftar semua akun pelanggan yang terdaftar di sistem.</p>

    <div class="card shadow mb-4">
        {{-- Tombol Tambah akan kita fungsikan nanti --}}
        {{-- <div class="card-header py-3">
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary">Tambah Pelanggan Baru</a>
        </div> --}}
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggan as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    {{-- Tombol Edit akan kita fungsikan nanti --}}
                                    {{-- <a href="{{ route('admin.pelanggan.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a> --}}
                                    <form action="{{ route('admin.pelanggan.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pelanggan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
