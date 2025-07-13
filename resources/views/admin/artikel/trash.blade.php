@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Arsip Artikel</h2>
                <p class="text-gray-600">Daftar semua artikel yang telah dihapus sementara.</p>
            </div>
            <a href="{{ route('admin.artikel.index') }}" class="text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Daftar Artikel
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artikel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dihapus Pada</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($artikels as $artikel)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $artikel->judul }}</div>
                                <div class="text-sm text-gray-500">{{ $artikel->author }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $artikel->deleted_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button"
                                     class="text-green-600 hover:text-green-900"
                                     onclick="openRestoreModal('{{ route('admin.artikel.restore', $artikel->id) }}')">
                                     Restore</button>
                                <button type="button"
                                    class="text-red-600 hover:text-red-900 ml-4"
                                    onclick="confirmForceDelete('{{ route('admin.artikel.forceDelete', $artikel->id) }}')">
                                    Hapus Permanen
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Arsip kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $artikels->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- JavaScript untuk Modal Restore ---
    function openRestoreModal(restoreUrl) {
        Swal.fire({
            title: '<i class="fas fa-undo text-green-600"></i> Konfirmasi Pemulihan',
            html: "Anda yakin ingin memulihkan artikel ini?",
            showCancelButton: true,
            confirmButtonColor: '#10b981', // green
            cancelButtonColor: '#6b7280',  // gray
            confirmButtonText: 'Ya, pulihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = restoreUrl;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PATCH';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // --- JavaScript untuk Modal Hapus Permanen ---
    function confirmForceDelete(deleteUrl) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: "Data ini akan dihapus selamanya dan tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Tailwind red-600
            cancelButtonColor: '#6b7280', // Tailwind gray-500
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form secara dinamis dan submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRestoreModal();
            closeForceDeleteModal();
        }
    });
</script>
@endpush
