@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <!-- Header Halaman -->
        <div>
            <h2 class="text-2xl font-semibold leading-tight">Manajemen Artikel</h2>
            <p class="text-gray-600">Kelola semua artikel dan kategori yang ada di sistem.</p>
        </div>

        <!-- Card Statistik -->
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

        <!-- Notifikasi Sukses (Untuk Artikel & Kategori) -->
        @if (session('success'))
            <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('success-alert').style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif
         <div id="category-success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert"></div>

        <!-- Tabel Daftar Artikel -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Daftar Postingan Artikel</h3>
                <a href="{{ route('admin.artikel.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Create Post Artikel
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artikel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($artikels as $artikel)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : 'https://placehold.co/40x40/e2e8f0/e2e8f0' }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($artikel->judul, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ $artikel->author }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $artikel->kategoriArtikel->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($artikel->status == 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada artikel yang diposting.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $artikels->links() }}
            </div>
        </div>

        <!-- Tabel Daftar Kategori -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Daftar Kategori Artikel</h3>
        <button onclick="openCreateCategoryModal()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Create Kategori
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white" id="kategori-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($kategoriArtikels as $kategori)
                <tr id="kategori-row-{{ $kategori->id }}">  {{-- Tambahkan ID unik untuk setiap baris --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 nama-kategori">{{ $kategori->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 slug-kategori">{{ $kategori->slug }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        {{-- Perbarui tombol Edit --}}
                        <button onclick="openEditCategoryModal({{ $kategori->id }}, '{{ $kategori->nama }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                    </td>
                </tr>
                @empty
                <tr id="no-kategori-row">
                    <td colspan="3" class="text-center py-4">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $kategoriArtikels->links() }}
    </div>
</div>
<div id="edit-category-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="edit-category-form" onsubmit="handleCategoryUpdate(event)">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Kategori</h3>
                    <div class="mt-4">
                        <label for="edit-kategori-nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" name="nama" id="edit-kategori-nama" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p id="edit-kategori-error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Update
                    </button>
                    <button type="button" onclick="closeEditCategoryModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Create Kategori -->
<div id="create-category-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="create-category-form" onsubmit="handleCategorySubmit(event)">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Buat Kategori Baru
                    </h3>
                    <div class="mt-4">
                        <label for="kategori-nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" name="nama" id="kategori-nama" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p id="kategori-error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeCreateCategoryModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Fungsi untuk membuka modal
    function openCreateCategoryModal() {
        document.getElementById('create-category-modal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal
    function closeCreateCategoryModal() {
        document.getElementById('create-category-modal').classList.add('hidden');
        document.getElementById('create-category-form').reset();
        document.getElementById('kategori-error').classList.add('hidden');
    }

    // Fungsi untuk menangani submit form kategori via AJAX
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
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const result = await response.json();

            if (!response.ok) {
                // Tampilkan error validasi
                if(result.errors && result.errors.nama) {
                    errorP.textContent = result.errors.nama[0];
                    errorP.classList.remove('hidden');
                }
                throw new Error('Network response was not ok');
            }

            // Tampilkan notifikasi sukses
            const successAlert = document.getElementById('category-success-alert');
            successAlert.innerHTML = `<strong>Sukses!</strong> ${result.message} <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">&times;</span>`;
            successAlert.classList.remove('hidden');

            // Tambahkan data baru ke tabel
            const tableBody = document.querySelector('#kategori-table tbody');
            const noDataRow = document.getElementById('no-kategori-row');
            if(noDataRow) {
                noDataRow.remove();
            }

            const newRow = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${result.data.nama}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${result.data.slug}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', newRow);

            // Tutup modal
            closeCreateCategoryModal();

        } catch (error) {
            console.error('Error:', error);
            errorP.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            errorP.classList.remove('hidden');
        }
    }

    // Fungsi untuk membuka modal EDIT
    function openEditCategoryModal(id, currentName) {
        const modal = document.getElementById('edit-category-modal');
        const form = document.getElementById('edit-category-form');
        const nameInput = document.getElementById('edit-kategori-nama');

        // Set action form ke URL update yang benar
        let url = "{{ route('admin.kategori-artikel.update', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;

        // Isi form dengan nama saat ini
        nameInput.value = currentName;

        modal.classList.remove('hidden');
    }

    // Fungsi untuk menutup modal EDIT
    function closeEditCategoryModal() {
        document.getElementById('edit-category-modal').classList.add('hidden');
        document.getElementById('edit-category-form').reset();
        document.getElementById('edit-kategori-error').classList.add('hidden');
    }

    // Fungsi untuk menangani submit form UPDATE kategori via AJAX
    async function handleCategoryUpdate(event) {
        event.preventDefault();
        const form = event.target;
        const url = form.action; // URL sudah benar dari fungsi openEditCategoryModal
        const errorP = document.getElementById('edit-kategori-error');
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const categoryName = document.getElementById('edit-kategori-nama').value;

        errorP.classList.add('hidden');

        try {
            const response = await fetch(url, {
                method: 'PUT', // <-- Perubahan 1: Kita kirim sebagai PUT langsung
                headers: {
                    'Content-Type': 'application/json', // <-- Perubahan 2: Tentukan tipe konten adalah JSON
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ // <-- Perubahan 3: Kirim data sebagai string JSON
                    nama: categoryName
                })
            });

            const result = await response.json();

            if (!response.ok) {
                // Logika untuk menampilkan error validasi dari server
                if(result.errors && result.errors.nama) {
                    errorP.textContent = result.errors.nama[0];
                    errorP.classList.remove('hidden');
                } else {
                    errorP.textContent = 'Terjadi kesalahan saat update.';
                    errorP.classList.remove('hidden');
                }
                throw new Error('Update failed');
            }

            // --- Logika jika sukses (tetap sama) ---
            const successAlert = document.getElementById('category-success-alert');
            successAlert.innerHTML = `<strong>Sukses!</strong> ${result.message} <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">&times;</span>`;
            successAlert.classList.remove('hidden');

            const row = document.getElementById(`kategori-row-${result.data.id}`);
            row.querySelector('.nama-kategori').textContent = result.data.nama;
            row.querySelector('.slug-kategori').textContent = result.data.slug;

            closeEditCategoryModal();

        } catch (error) {
            console.error('Error:', error);
        }
    }

</script>
@endpush
