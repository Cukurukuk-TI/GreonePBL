@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div>
            <h2 class="text-2xl font-semibold leading-tight">Manajemen Artikel</h2>
            <p class="text-gray-600">Kelola semua artikel dan kategori yang ada di sistem.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 my-6">
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600">Artikel di Post</p>
                        <p class="text-2xl font-bold">{{ $totalArtikel }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-500">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600">Artikel Dihapus</p>
                        <p class="text-2xl font-bold">{{ $artikelDihapus }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div id="alert-notification" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('alert-notification').style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif
        {{-- Div ini akan diisi oleh AJAX untuk notifikasi kategori --}}
        <div id="ajax-alert-container"></div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Daftar Postingan Artikel</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.artikel.trash') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-archive mr-2"></i>Arsip
                    </a>
                    <a href="{{ route('admin.artikel.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>Buat Artikel
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Artikel</th>
                            <th class="px-6 py-3 text-left">Kategori</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($artikels as $artikel)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : 'https://placehold.co/40x40/e2e8f0/e2e8f0' }}"
                                             alt="Gambar Artikel">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($artikel->judul, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ $artikel->author }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $artikel->kategoriArtikel->nama ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if ($artikel->status === 'published')
                                    <span class="px-2 inline-flex text-xs font-semibold leading-5 rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs font-semibold leading-5 rounded-full bg-gray-100 text-gray-800">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="text-indigo-600 hover:text-indigo-900 transition">Edit</a>
                                <form action="{{ route('admin.artikel.destroy', $artikel->id) }}" method="POST" class="inline delete-form ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 hover:text-red-900 btn-delete transition" data-title="Anda yakin ingin memindahkan artikel ini ke arsip?">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-sm text-gray-500">Belum ada artikel yang diposting.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $artikels->links() }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Daftar Kategori Artikel</h3>

                <button onclick="openCreateCategoryPrompt()" class="bg-blue-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-plus mr-2"></i> Tambah Kategori
                </button>

            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white" id="kategori-table">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama Kategori</th>
                            <th class="px-6 py-3 text-left">Unique Keyword (Slug)</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kategoriArtikels as $kategori)
                        <tr id="kategori-row-{{ $kategori->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 nama-kategori">{{ $kategori->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 slug-kategori">{{ $kategori->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <!-- Tombol Edit -->
                                <button 
                                    onclick="openEditCategoryModal({{ $kategori->id }}, '{{ $kategori->nama }}')" 
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </button>

                                <!-- Tombol Hapus dengan SweetAlert -->
                                <form 
                                    action="{{ route('admin.kategori-artikel.destroy', $kategori->id) }}" 
                                    method="POST" 
                                    class="inline delete-form ml-4">
                                    
                                    @csrf
                                    @method('DELETE')

                                    <button 
                                        type="button" 
                                        class="text-red-600 hover:text-red-900 btn-delete"
                                        data-title="Yakin ingin menghapus kategori ini permanen?">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="no-kategori-row">
                            <td colspan="3" class="text-center py-4 text-gray-500">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kategoriArtikels->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Menampilkan notifikasi AJAX universal.
     * @param {string} message - Pesan yang akan ditampilkan.
     * @param {string} type - Tipe notifikasi ('success', 'error').
     */
    function showAjaxAlert(message, type = 'success') {
        const container = document.getElementById('ajax-alert-container');
        const alertColor = type === 'success' ? 'green' : 'red';
        
        container.innerHTML = `
            <div class="bg-${alertColor}-100 border border-${alertColor}-400 text-${alertColor}-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">${type === 'success' ? 'Sukses!' : 'Error!'}</strong>
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
                    &times;
                </span>
            </div>
        `;
    }

    function openCreateCategoryPrompt() {
        Swal.fire({
            title: 'Buat Kategori Baru',
            html:
                `<input id="swal-input-kategori" type="text" placeholder="Nama kategori" class="swal2-input">`,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb', // Tailwind blue-600
            cancelButtonColor: '#9ca3af',  // Tailwind gray-400
            preConfirm: () => {
                const nama = document.getElementById('swal-input-kategori').value;
                if (!nama) {
                    Swal.showValidationMessage('Nama kategori harus diisi');
                    return false;
                }

                return fetch("{{ route('admin.kategori-artikel.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nama: nama })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal menambahkan kategori');
                    return response.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Gagal menyimpan kategori');
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(error.message);
                });
            }
        }).then((result) => {
        if (result.isConfirmed && result.value?.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.value.message
            });

            const kategori = result.value.data;
            const tableBody = document.querySelector('#kategori-table tbody');

            // Hapus row kosong jika ada
            const emptyRow = document.getElementById('no-kategori-row');
            if (emptyRow) emptyRow.remove();

            // Buat baris baru
            const newRow = document.createElement('tr');
            newRow.id = `kategori-row-${kategori.id}`;
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 nama-kategori">${kategori.nama}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 slug-kategori">${kategori.slug}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="openEditCategoryModal(${kategori.id}, '${kategori.nama}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    <button onclick="openDeleteModal('/admin/kategori-artikel/${kategori.id}', 'Anda yakin ingin menghapus kategori ini secara permanen?')" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                </td>
            `;
            tableBody.prepend(newRow);
        }
    });
};

    // --- Modal Edit ---
function openEditCategoryModal(id, currentNama) {
    Swal.fire({
        title: 'Edit Kategori',
        html:
            `<input id="swal-input-edit" type="text" value="${currentNama}" class="swal2-input" placeholder="Nama kategori">`,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb', // Tailwind blue
        cancelButtonColor: '#9ca3af',
        preConfirm: () => {
            const newNama = document.getElementById('swal-input-edit').value.trim();
            if (!newNama) {
                Swal.showValidationMessage('Nama kategori tidak boleh kosong');
                return false;
            }

            return fetch(`/admin/kategori-artikel/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nama: newNama })
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengupdate kategori');
                return response.json();
            })
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Update gagal');
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(error.message);
            });
        }
    }).then(result => {
        if (result.isConfirmed && result.value?.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.value.message,
                timer: 1500,
                showConfirmButton: false
            });

            // Update nama dan slug di tabel
            const row = document.getElementById(`kategori-row-${id}`);
            if (row) {
                row.querySelector('.nama-kategori').textContent = result.value.data.nama;
                row.querySelector('.slug-kategori').textContent = result.value.data.slug;
            }
        }
    });
}


    /*
    --- KODE JAVASCRIPT DI BAWAH INI DIJADIKAN KOMENTAR (REDUNDAN) ---
    Fungsi ini tidak diperlukan lagi karena konfirmasi hapus sudah ditangani oleh SweetAlert.
    
    function openDeleteModal(deleteUrl, message) { ... }
    function closeDeleteModal() { ... }
    */

    // --- Logika AJAX untuk Form Kategori ---

    async function handleCategorySubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const errorP = document.getElementById('kategori-error');
        errorP.classList.add('hidden');

        try {
            const response = await fetch("{{ route('admin.kategori-artikel.store') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (!response.ok) {
                if(result.errors && result.errors.nama) {
                    errorP.textContent = result.errors.nama[0];
                    errorP.classList.remove('hidden');
                }
                throw new Error('Validasi gagal');
            }

            // Tampilkan notifikasi dan reload halaman agar data konsisten
            showAjaxAlert(result.message);
            closeCreateCategoryModal();
            // Reload halaman untuk menampilkan data terbaru (termasuk paginasi)
            setTimeout(() => window.location.reload(), 1500);

        } catch (error) {
            console.error('Error:', error);
            errorP.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            errorP.classList.remove('hidden');
        }
    }

    async function handleCategoryUpdate(event) {
        event.preventDefault();
        const form = event.target;
        const url = form.action;
        const errorP = document.getElementById('edit-kategori-error');
        errorP.classList.add('hidden');
        
        // Menggunakan FormData untuk kemudahan
        const formData = new FormData(form);
        // Kita perlu menambahkan _method secara manual karena FormData tidak mengambilnya dari @method('PUT')
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST', // Fetch API tidak mendukung PUT secara langsung dalam form-data, jadi kita 'tunnel' melalui POST
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const result = await response.json();

            if (!response.ok) {
                if(result.errors && result.errors.nama) {
                    errorP.textContent = result.errors.nama[0];
                    errorP.classList.remove('hidden');
                } else {
                    errorP.textContent = result.message || 'Terjadi kesalahan saat update.';
                    errorP.classList.remove('hidden');
                }
                throw new Error('Update failed');
            }

            // Update baris tabel secara dinamis
            const row = document.getElementById(`kategori-row-${result.data.id}`);
            if (row) {
                row.querySelector('.nama-kategori').textContent = result.data.nama;
                row.querySelector('.slug-kategori').textContent = result.data.slug;
            }
            
            showAjaxAlert(result.message);
            closeEditCategoryModal();

        } catch (error) {
            console.error('Error:', error);
        }
    }

    // --- Konfirmasi Hapus dengan SweetAlert ---
    document.addEventListener('DOMContentLoaded', () => {
        // Event listener ini berlaku untuk SEMUA tombol dengan class .btn-delete
        document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete')) {
                const button = e.target;
                const form = button.closest('form');
                const title = button.dataset.title || 'Anda yakin ingin menghapus data ini?';

                e.preventDefault(); // Mencegah form submit langsung

                Swal.fire({
                    title: title,
                    text: "Aksi ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Lanjutkan submit form jika dikonfirmasi
                    }
                });
            }
        });
    });

</script>
@endpush