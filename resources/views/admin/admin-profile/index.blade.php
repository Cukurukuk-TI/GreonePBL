@extends('layouts.admin-profile')

@section('title', 'Profil Saya')

@section('content')
    <div class="w-full min-h-full flex items-center justify-center p-4 sm:p-6 lg:p-8">

        <div class="max-w-2xl w-full bg-white rounded-xl shadow-lg overflow-hidden my-8">
            
            <div class="relative">
                <img class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1557682250-33bd709cbe85?q=80&w=2070&auto=format&fit=crop" alt="Banner Profil">
                
                <div class="absolute top-4 right-4">
                    <a href="#" class="bg-white/30 backdrop-blur-sm text-white hover:bg-white/50 font-semibold py-2 px-4 rounded-lg text-sm transition-colors duration-200">
                        <i class="fas fa-pencil-alt mr-2"></i>
                        Edit Profil
                    </a>
                </div>

                <div class="absolute -bottom-16 left-1/2 -translate-x-1/2">
                    <img class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg" 
                         src="https://ui-avatars.com/api/?name=Admin+BGD&background=005E25&color=fff&size=128" 
                         alt="Foto Profil">
                </div>
            </div>

            <div class="text-center pt-20 pb-6 px-6">
                <h2 class="text-3xl font-bold text-brand-text">Nama Admin</h2>
                <p class="text-md text-brand-text-muted mt-1">Administrator</p>
            </div>

            <div class="px-6 sm:px-8 pb-8">
                <h3 class="text-lg font-semibold text-brand-text border-t border-gray-200 pt-6">Detail Informasi Kontak</h3>
                <div class="mt-4 space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 text-center text-brand-text-muted flex-shrink-0"><i class="fas fa-envelope"></i></div>
                        <div class="ml-4">
                            <p class="text-xs text-brand-text-muted">Alamat Email</p>
                            <p class="font-semibold text-brand-text break-all">admin@example.com</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 text-center text-brand-text-muted flex-shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="ml-4">
                            <p class="text-xs text-brand-text-muted">Alamat</p>
                            <p class="font-semibold text-brand-text">Jalan Jenderal Sudirman No. 123, Padang, Sumatera Barat</p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-brand-text border-t border-gray-200 pt-6 mt-8">Informasi Pribadi</h3>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                     <div class="flex items-start">
                        <div class="w-8 text-center text-brand-text-muted flex-shrink-0"><i class="fas fa-venus-mars"></i></div>
                        <div class="ml-4">
                            <p class="text-xs text-brand-text-muted">Jenis Kelamin</p>
                            <p class="font-semibold text-brand-text">Laki-laki</p>
                        </div>
                    </div>
                     <div class="flex items-start">
                        <div class="w-8 text-center text-brand-text-muted flex-shrink-0"><i class="fas fa-birthday-cake"></i></div>
                        <div class="ml-4">
                            <p class="text-xs text-brand-text-muted">Tanggal Lahir</p>
                            <p class="font-semibold text-brand-text">01 Januari 2000</p>
                        </div>
                    </div>
                     <div class="flex items-start">
                        <div class="w-8 text-center text-brand-text-muted flex-shrink-0"><i class="fas fa-calendar-alt"></i></div>
                        <div class="ml-4">
                            <p class="text-xs text-brand-text-muted">Member Sejak</p>
                            <p class="font-semibold text-brand-text">18 Juni 2025</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection