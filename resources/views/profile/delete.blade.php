@extends('layouts.profile')

@section('profile-content')
    {{-- Menggunakan kembali form yang sudah ada --}}
    @include('profile.partials.update-password-form')
@endsection
