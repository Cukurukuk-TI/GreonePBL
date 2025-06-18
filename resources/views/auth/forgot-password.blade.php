@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-10 bg-white rounded-xl shadow-xl">
        <h2 class="text-3xl font-bold text-center mb-4 text-gray-700">Lupa Password</h2>
        <p class="text-center text-gray-500 mb-8">
            Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </p>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-700 bg-green-100 p-3 rounded-md text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    required
                    autofocus
                    class="w-full border px-3 py-2 rounded-md shadow-sm @error('email') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition duration-200">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Tiba-tiba ingat password Anda?
            <a href="{{ route('login') }}" class="text-green-600 hover:underline font-medium">Kembali ke Login</a>
        </p>
    </div>
</div>
@endsection
