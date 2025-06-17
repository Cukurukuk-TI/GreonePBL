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

@push('scripts')
<script>
    // Inisialisasi peta
    const lat = parseFloat(document.getElementById('latitude').value) || -0.949242;
    const lng = parseFloat(document.getElementById('longitude').value) || 100.354263;

    const map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);

    // --- BAGIAN SINKRONISASI PETA KE FORM ---
    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        const newLat = position.lat;
        const newLng = position.lng;

        // 1. Update input hidden dengan koordinat baru
        document.getElementById('latitude').value = newLat.toFixed(6);
        document.getElementById('longitude').value = newLng.toFixed(6);

        // 2. Lakukan Reverse Geocoding untuk mendapatkan detail alamat
        // Menggunakan API gratis Nominatim
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${newLat}&lon=${newLng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    // 3. Isi field form dengan data dari peta
                    document.querySelector('[name="detail_alamat"]').value = data.display_name || '';
                    document.querySelector('[name="kota"]').value = data.address.city || data.address.town || data.address.county || '';
                    document.querySelector('[name="provinsi"]').value = data.address.state || '';
                }
            })
            .catch(error => {
                console.error('Error fetching address:', error);
                document.querySelector('[name="detail_alamat"]').value = `Koordinat: ${newLat.toFixed(5)}, ${newLng.toFixed(5)}`;
            });
    });
</script>
@endpush
