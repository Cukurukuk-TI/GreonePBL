@extends('layouts.profileuser')
@section('profile-content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-2xl mx-auto mt-6">

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

    <!-- Tombol Edit -->
    <div class="text-right">
        <a href="{{ route('profile.edit') }}"
           class="inline-flex items-center px-4 py-2 border border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-50 transition-colors">
            Edit Profil
        </a>
    </div>

</div>
@endsection
