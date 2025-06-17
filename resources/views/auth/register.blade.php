@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 pt-0 mt-[-30px]">
    <div class="w-full max-w-xl p-10 bg-white rounded-xl shadow-xl">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-700">Buat Akun Baru</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-center font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-6">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="name" class="block font-semibold mb-1">Nama</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    value="{{ old('name') }}" 
                    placeholder="Nama lengkap"
                    required
                    class="w-full border px-3 py-2 rounded @error('name') border-red-500 @enderror focus:outline-none focus:border-blue-400"
                />
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}" 
                    placeholder="Email aktif"
                    required
                    class="w-full border px-3 py-2 rounded @error('email') border-red-500 @enderror focus:outline-none focus:border-blue-400"
                />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="relative">
                <label for="password" class="block font-semibold mb-1">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Minimal 8 karakter"
                    required
                    class="w-full border px-3 py-2 rounded pr-10 @error('password') border-red-500 @enderror focus:outline-none focus:border-blue-400"
                />
                <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute right-3 top-9 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Toggle password visibility">
                    <svg id="toggleIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="relative">
                <label for="password_confirmation" class="block font-semibold mb-1">Konfirmasi Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    placeholder="Ulangi password"
                    required
                    class="w-full border px-3 py-2 rounded pr-10 focus:outline-none focus:border-blue-400"
                />
                <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" class="absolute right-3 top-9 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Toggle password visibility">
                    <svg id="toggleIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>

            {{-- Tombol Register --}}
            <button type="submit"
                class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition duration-200">
                Daftar
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="/login" class="text-green-600 hover:underline font-medium">Login di sini</a>
        </p>
    </div>
</div>

{{-- Script Toggle Password --}}
<script>
function togglePassword(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';

    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}
</script>
@endsection
