{{-- resources/views/alamat/create.blade.php --}}
@extends('layouts.alamat')

@section('alamat-content')
<div class="max-w-xl bg-white p-6 rounded shadow">
    <h2 class="text-lg font-bold mb-4">Tambah Alamat Baru</h2>
    <form action="{{ route('alamat.store') }}" method="POST">
        @csrf

        @include('alamat.form')

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-3">Simpan</button>
    </form>
</div>
@endsection
