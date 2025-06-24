@extends('layouts.atminnav')

@section('title', 'Informasi Akun')

@section('content')
    {{-- Kartu Profil Utama --}}
    <div class="bg-white rounded-xl shadow-md max-w-2xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between sm:items-center p-6 border-b border-gray-200">
            <div>
                <h2 class="text-2xl font-bold text-brand-text">Informasi Akun</h2>
                <p class="text-sm text-brand-text-muted mt-1">Detail data personal Anda yang terdaftar di sistem.</p>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="mt-4 sm:mt-0 inline-block bg-brand-green hover:bg-brand-green-dark text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm">
                <i class="fas fa-pencil-alt mr-2"></i>Edit Profil
            </a>
        </div>

        <div class="flex flex-col items-center p-8 text-center">
            @if(auth()->user()->foto)
                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil"
                     class="h-28 w-28 rounded-full object-cover border-4 border-brand-green-light shadow-md">
            @else
                <div class="h-28 w-28 rounded-full bg-brand-green flex items-center justify-center text-white text-4xl font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            <h3 class="text-2xl font-bold text-brand-text mt-4">{{ auth()->user()->name }}</h3>
            <p class="text-md text-brand-text-muted">{{ auth()->user()->email }}</p>
        </div>

        {{-- Bagian Detail Akun --}}
        <div class="border-t border-gray-200">
            <dl class="divide-y divide-gray-200">
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-brand-text-muted">Terakhir Aktif</dt>
                    <dd class="text-sm text-brand-text col-span-2 font-semibold">
                        {{ now()->format('l, d F Y H:i') }}
                    </dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-brand-text-muted">Role</dt>
                    <dd class="text-sm text-brand-text col-span-2 font-semibold">
                        Administrator
                    </dd>
                </div>
            </dl>
        </div>
        
        {{-- Logout --}}
        <div class="p-6 bg-gray-50 rounded-b-xl text-right">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" onclick="confirmLogout()" class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md shadow-sm text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Anda yakin ingin keluar dari akun Anda?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush
