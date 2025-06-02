{{-- resources/views/alamat/form.blade.php --}}
@php
    $alamat = $alamat ?? new \App\Models\Alamat;
@endphp

{{-- Pilihan label enum --}}
<select name="label" class="w-full border px-3 py-2 mb-2 rounded">
    <option value="">-- Pilih Label Alamat --</option>
    <option value="rumah" {{ old('label', $alamat->label) == 'rumah' ? 'selected' : '' }}>Rumah</option>
    <option value="kantor" {{ old('label', $alamat->label) == 'kantor' ? 'selected' : '' }}>Kantor</option>
    <option value="other" {{ old('label', $alamat->label) == 'other' ? 'selected' : '' }}>Lainnya</option>
</select>

{{-- Nama penerima --}}
<input name="nama_penerima" placeholder="Nama Penerima" class="w-full border px-3 py-2 mb-2 rounded"
    value="{{ old('nama_penerima', $alamat->nama_penerima) }}">

{{-- Nomor HP --}}
<input name="nomor_hp" placeholder="Nomor HP" class="w-full border px-3 py-2 mb-2 rounded"
    value="{{ old('nomor_hp', $alamat->nomor_hp) }}">

{{-- Provinsi --}}
<input name="provinsi" placeholder="Provinsi" class="w-full border px-3 py-2 mb-2 rounded"
    value="{{ old('provinsi', $alamat->provinsi) }}">

{{-- Kota --}}
<input name="kota" placeholder="Kota" class="w-full border px-3 py-2 mb-2 rounded"
    value="{{ old('kota', $alamat->kota) }}">

{{-- Detail alamat --}}
<textarea name="detail_alamat" placeholder="Detail Alamat"
    class="w-full border px-3 py-2 mb-2 rounded">{{ old('detail_alamat', $alamat->detail_alamat) }}</textarea>
