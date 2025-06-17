@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 pt-0 mt-[-30px]">
    <div class="w-full max-w-lg p-10 bg-white rounded-xl shadow-xl">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-700">Masuk ke Akun Anda</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-center font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-6">
            @csrf

            {{-- Email --}}
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

            {{-- Password --}}
            <div class="relative">
                <label for="password" class="block font-semibold mb-1">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Masukkan password"
                    required 
                    class="w-full border px-3 py-2 rounded-md shadow-sm @error('password') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10"
                />
                {{-- Toggle Password --}}
                <button type="button" onclick="togglePassword()" 
                    class="absolute right-3 top-9 text-gray-500 hover:text-gray-700 transition" 
                    aria-label="Toggle password visibility">
                    <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 
                            4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me & Lupa Password --}}
            <div class="flex items-center justify-between text-sm text-gray-600">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="form-checkbox text-green-600">
                    <span>Ingat saya</span>
                </label>
                <a href="/password/reset" class="text-blue-500 hover:underline">Lupa Password?</a>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition duration-200">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="/register" class="text-green-600 hover:underline font-medium">Daftar di sini</a>
        </p>
    </div>
</div>

{{-- Toggle Password Script --}}
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    const isPassword = passwordInput.type === 'password';

    passwordInput.type = isPassword ? 'text' : 'password';

    toggleIcon.innerHTML = isPassword
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
            a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
            M9.878 9.878l4.242 4.242M3 3l18 18" />`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5
            c4.478 0 8.268 2.943 9.542 7-1.274 
            4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
}
</script>
@endsection