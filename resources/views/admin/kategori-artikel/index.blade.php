@extends('admin.artikel.layout')

@section('artikel_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Daftar Kategori Artikel</h3>
        {{-- Tombol Tambah akan kita fungsikan nanti --}}
        <a href="{{ route('admin.artikel.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoriArtikels as $index => $kategori)
                <tr>
                    <td>{{ $kategoriArtikels->firstItem() + $index }}</td>
                    <td>{{ $kategori->nama }}</td>
                    <td>{{ $kategori->slug }}</td>
                    <td>
                        {{-- Tombol Aksi (Edit & Hapus) akan kita tambahkan nanti --}}
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
     <!-- Paginasi -->
    <div class="d-flex justify-content-center">
        {{ $kategoriArtikels->links() }}
    </div>
@endsection
