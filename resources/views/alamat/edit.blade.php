@extends('layouts.alamat')

@section('alamat-content')
<div class="w-full max-w-4xl bg-white p-6 rounded-md shadow-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Perbarui Alamat</h2>
    <form action="{{ route('alamat.update', $alamat->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('alamat.form', ['alamat' => $alamat])

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow transition">
                Perbarui
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-sA+e2eZb10nM58n38DxtS+w4ja0Ithk1QNYu1l7gRlA=" crossorigin="" />

<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const defaultLat = -0.949242;  // Titik tengah Kota Padang
    const defaultLng = 100.354263;

    const lat = parseFloat(document.getElementById('latitude').value) || defaultLat;
    const lng = parseFloat(document.getElementById('longitude').value) || defaultLng;

    const map = L.map('map').setView([lat, lng], 14);

    // Tile Style mirip Google Maps
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(map);

    // Marker
    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    marker.bindPopup("Geser marker untuk menentukan lokasi").openPopup();

    marker.on('dragend', function () {
        const position = marker.getLatLng();
        const newLat = position.lat;
        const newLng = position.lng;

        document.getElementById('latitude').value = newLat.toFixed(6);
        document.getElementById('longitude').value = newLng.toFixed(6);

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${newLat}&lon=${newLng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
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
