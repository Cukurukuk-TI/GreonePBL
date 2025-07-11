@php
    $alamat = $alamat ?? new \App\Models\Alamat;
@endphp

<div class="space-y-4 w-full max-w-3xl mx-auto">

    {{-- Label Alamat --}}
    <div>
        <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Label Alamat</label>
        <select required name="label" id="label"
            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">-- Pilih Label Alamat --</option>
            <option value="rumah" {{ old('label', $alamat->label) == 'rumah' ? 'selected' : '' }}>Rumah</option>
            <option value="kantor" {{ old('label', $alamat->label) == 'kantor' ? 'selected' : '' }}>Kantor</option>
            <option value="other" {{ old('label', $alamat->label) == 'other' ? 'selected' : '' }}>Lainnya</option>
        </select>
    </div>

    {{-- Nama Penerima --}}
    <div>
        <label for="nama_penerima" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
        <input required type="text" name="nama_penerima" id="nama_penerima"
            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Nama Penerima"
            value="{{ old('nama_penerima', $alamat->nama_penerima) }}">
    </div>

    {{-- Nomor HP --}}
    <div>
        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
        <input required type="number" name="nomor_hp" id="nomor_hp"
            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="08xxxxxxxxxx"
            value="{{ old('nomor_hp', $alamat->nomor_hp) }}">
    </div>

    {{-- Provinsi --}}
    <div>
        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
        <input readonly type="text" name="provinsi" id="provinsi"
            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Pilih lokasi di peta — data akan terisi otomatis."
            value="{{ old('provinsi', $alamat->provinsi) }}">
    </div>

    {{-- Kota --}}
    <div>
        <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
        <input readonly type="text" name="kota" id="kota"
            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Pilih lokasi di peta — data akan terisi otomatis."
            value="{{ old('kota', $alamat->kota) }}">
    </div>

    {{-- Detail Alamat --}}
    <div>
        <label for="detail_alamat" class="block text-sm font-medium text-gray-700 mb-1">Detail Alamat</label>
        <textarea required name="detail_alamat" id="detail_alamat"
            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="Masukkan alamat lengkap">{{ old('detail_alamat', $alamat->detail_alamat) }}</textarea>
    </div>

    {{-- Map --}}
    <div class="mt-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lokasi di Peta</label>
        <div id="map" class="w-full h-80 rounded-md border border-gray-300"></div>
    </div>

    {{-- Hidden Latitude & Longitude --}}
    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $alamat->latitude) }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $alamat->longitude) }}">

</div>
