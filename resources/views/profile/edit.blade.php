@extends('layouts.profile')

@section('profile-content')
    {{-- Menggunakan kembali form yang sudah ada --}}
    @include('profile.partials.update-profile-information-form')
    <div class="mt-12 pt-8 border-t border-red-200">
        @include('profile.partials.delete-user-form')
    </div>

@endsection
