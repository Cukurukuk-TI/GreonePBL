@extends('layouts.atminnav')

@section('profile-content')
<div class="bg-white shadow-xl rounded-xl p-8 max-w-xl mx-auto mt-6">

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Informasi Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Ubah biodata dan foto profilmu di bawah ini</p>
    </div>

    <!-- Foto Profil Preview -->
    @if(auth()->user()->foto)
        <div class="flex justify-center mb-6">
            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil"
                 class="h-24 w-24 rounded-full object-cover border-4 border-blue-500 shadow-md">
        </div>
    @endif

    <!-- Biodata Display dalam satu kolom -->
    <div class="space-y-4 bg-gray-50 p-6 rounded-xl border border-gray-200 mb-10">
        <div>
            <p class="text-sm text-gray-500">Nama</p>
            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
        </div>

        @php
            $alamat = auth()->user()->alamat; // diasumsikan relasi hasOne
        @endphp

        <div>
            <p class="text-sm text-gray-500">Alamat</p>
            @if($alamat)
                <div class="text-gray-800 font-semibold space-y-1">
                    <p>{{ $alamat->label ? ucfirst($alamat->label) : 'Alamat Utama' }}</p>
                    <p>{{ $alamat->nama_penerima }} &bull; {{ $alamat->nomor_hp }}</p>
                    <p>{{ $alamat->detail_alamat }}</p>
                    <p>{{ $alamat->kota }}, {{ $alamat->provinsi }}</p>
                </div>
            @else
                <p class="font-semibold text-gray-800">-</p>
            @endif
        </div>

        <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-semibold text-gray-800">{{ auth()->user()->email }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Jenis Kelamin</p>
            <p class="font-semibold text-gray-800">{{ auth()->user()->jenis_kelamin ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Tanggal Lahir</p>
            <p class="font-semibold text-gray-800">
                @if(auth()->user()->tanggal_lahir)
                    {{ \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->format('d/m/Y') }}
                @else
                    -
                @endif
            </p>
        </div>
    </div>

<div class="text-right">
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="button" onclick="showLogoutModal()" class="inline-flex items-center px-4 py-2 border border-red-600 text-red-600 font-semibold rounded-lg hover:bg-red-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
        </button>
    </form>
</div>

<!-- Logout Modal (diletakkan di luar <nav> / <li>) -->
<div id="logout-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div id="logout-modal-content"
         class="bg-white rounded-lg shadow-lg p-6 transform scale-95 opacity-0 transition-all duration-300 max-w-md w-full">
        <h2 class="text-xl font-semibold mb-4 text-center">Konfirmasi Logout</h2>
        <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="document.getElementById('logout-form').submit()"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Ya, Logout
            </button>
            <button onclick="hideLogoutModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    function showLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const content = document.getElementById('logout-modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const content = document.getElementById('logout-modal-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection