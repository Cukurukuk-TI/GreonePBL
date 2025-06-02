{{-- resources/views/alamat/edit.blade.php --}}
@extends('layouts.alamat')

@section('alamat-content')
<div class="max-w-xl bg-white p-6 rounded shadow">
    <h2 class="text-lg font-bold mb-4">Perbarui Alamat</h2>
    <form action="{{ route('alamat.update', $alamat->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('alamat.form', ['alamat' => $alamat])

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-3">Perbarui</button>
    </form>
</div>
@endsection
