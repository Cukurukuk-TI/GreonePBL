@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-10 bg-white rounded-xl shadow-xl">
        <h2 class="text-3xl font-bold text-center mb-4 text-gray-700">Atur Password Baru</h2>
        <p class="text-center text-gray-500 mb-8">
            Buat password baru untuk akun yang terhubung dengan email di bawah ini.
        </p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input
                    type="email"
                    id="email"
                    value="{{ request('email') }}"
                    class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-100 text-gray-600 cursor-not-allowed"
                    readonly
                >
                {{-- Input email tersembunyi untuk dikirim bersama form --}}
                <input type="hidden" name="email" value="{{ request('email') }}">

                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block font-semibold mb-1">Password Baru</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Masukkan password baru"
                    required
                    autofocus
                    class="w-full border px-3 py-2 rounded-md shadow-sm @error('password') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block font-semibold mb-1">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    placeholder="Ketik ulang password baru Anda"
                    required
                    class="w-full border px-3 py-2 rounded-md shadow-sm"
                />
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition duration-200">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
