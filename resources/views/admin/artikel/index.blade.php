@extends('admin.artikel.layout')

@section('artikel_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Daftar Artikel</h3>
        {{-- Tombol Tambah akan kita fungsikan nanti --}}
        <a href="{{ route('admin.artikel.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Artikel
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Tanggal Post</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($artikels as $index => $artikel)
                <tr>
                    <td>{{ $artikels->firstItem() + $index }}</td>
                    <td>{{ $artikel->judul }}</td>
                    <td>{{ $artikel->kategoriArtikel->nama ?? 'Tanpa Kategori' }}</td>
                    <td>{{ $artikel->author }}</td>
                    <td>
                        @if ($artikel->status == 'published')
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                    </td>
                    <td>{{ $artikel->tanggal_post->format('d M Y') }}</td>
                    <td>
                        {{-- Tombol Aksi (Edit & Hapus) akan kita tambahkan nanti --}}
                        <a href="#" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada artikel.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Paginasi -->
    <div class="d-flex justify-content-center">
        {{ $artikels->links() }}
    </div>
@endsection
