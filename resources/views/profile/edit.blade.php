@extends('layouts.profileuser')

@section('profile-content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-3xl mx-auto mt-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi, keamanan, dan privasi akun Anda.</p>
    </div>

    <div x-data="{ tab: 'profile' }">
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex -mb-px space-x-6">
                <a href="#" @click.prevent="tab = 'profile'"
                   :class="{ 'border-blue-500 text-blue-600': tab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'profile' }"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Profil
                </a>
                <a href="#" @click.prevent="tab = 'password'"
                   :class="{ 'border-blue-500 text-blue-600': tab === 'password', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'password' }"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Ubah Password
                </a>
            </nav>
        </div>

        <div>
            <div x-show="tab === 'profile'" x-cloak>
                @include('profile.partials.update-profile-information-form')
            </div>
            <div x-show="tab === 'password'" x-cloak>
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
