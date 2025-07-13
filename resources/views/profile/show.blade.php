@extends('layouts.profile')

@section('profile-content')
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Profil Saya</h2>
    <p class="text-gray-500 mb-8">Ini adalah ringkasan informasi akun Anda.</p>

    <div class="space-y-4">
        <div class="flex justify-between items-center py-3 border-b">
            <span class="text-gray-600">Nama Lengkap</span>
            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
        </div>
        <div class="flex justify-between items-center py-3 border-b">
            <span class="text-gray-600">Alamat Email</span>
            <span class="font-semibold text-gray-800">{{ $user->email }}</span>
        </div>
        <div class="flex justify-between items-center py-3 border-b">
            <span class="text-gray-600">Tanggal Lahir</span>
            <span class="font-semibold text-gray-800">{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') : '-' }}</span>
        </div>
        <div class="flex justify-between items-center py-3 border-b">
            <span class="text-gray-600">Bergabung Sejak</span>
            <span class="font-semibold text-gray-800">{{ $user->created_at->format('d F Y') }}</span>
        </div>
    </div>

    <div class="mt-8 text-right">
        <a href="{{ route('profile.edit') }}" class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-green-700 transition">
            Edit Profil
        </a>
    </div>
@endsection
