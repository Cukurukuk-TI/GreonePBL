@extends('layouts.appnoslider')

@section('title', 'Lakukan Pembayaran')

@push('scripts_head')
    {{-- Muat skrip Midtrans di head agar siap digunakan --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 md:py-16">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        
        <div class="text-center border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Selesaikan Pembayaran</h1>
            <p class="text-gray-500 mt-1">Pesanan Anda telah dibuat. Segera lakukan pembayaran.</p>
        </div>

        {{-- Notifikasi --}}
        <div id="payment-notification" class="hidden mb-4 p-4 text-sm rounded-lg" role="alert"></div>

        {{-- Rincian Pesanan --}}
        <div class="space-y-4">
            <div class="flex justify-between text-gray-600">
                <span>Kode Pesanan:</span>
                <span class="font-semibold text-gray-800">{{ $pesanan->kode_pesanan }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Tanggal Pesanan:</span>
                <span class="font-semibold text-gray-800">{{ $pesanan->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</span>
            </div>
            <div class="border-t my-4"></div>
            <div class="flex justify-between text-lg font-bold text-gray-800">
                <span>Total Pembayaran:</span>
                <span class="text-green-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-8">
            <button id="pay-button" class="w-full bg-green-600 text-white font-bold py-4 px-6 rounded-lg hover:bg-green-700 transition transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span id="button-text">Bayar Sekarang</span>
                <div id="button-spinner" class="hidden w-5 h-5 border-t-2 border-r-2 border-white rounded-full animate-spin mx-auto"></div>
            </button>
            <div class="text-center mt-4">
                <a href="{{ route('user.pesanan') }}" class="text-sm text-gray-500 hover:text-gray-700">Bayar Nanti (Lihat di Pesanan Saya)</a>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('pay-button');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');
    const notificationDiv = document.getElementById('payment-notification');

    function showNotification(message, type = 'error') {
        notificationDiv.className = 'mb-4 p-4 text-sm rounded-lg'; // Reset class
        if (type === 'error') {
            notificationDiv.classList.add('bg-red-100', 'text-red-700');
        } else {
            notificationDiv.classList.add('bg-yellow-100', 'text-yellow-700');
        }
        notificationDiv.textContent = message;
        notificationDiv.classList.remove('hidden');
    }

    payButton.addEventListener('click', function () {
        // Tampilkan loading
        buttonText.classList.add('hidden');
        buttonSpinner.classList.remove('hidden');
        payButton.disabled = true;

        fetch('{{ route("pesanan.pay", $pesanan->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = `/pesanan/success/{{$pesanan->id}}?status=success`;
                    },
                    onPending: function(result) {
                        window.location.href = `/pesanan/success/{{$pesanan->id}}?status=pending`;
                    },
                    onError: function(result) {
                        showNotification('Pembayaran gagal. Silakan coba lagi.', 'error');
                        resetButton();
                    },
                    onClose: function() {
                        showNotification('Anda menutup jendela pembayaran.', 'warning');
                        // Arahkan ke halaman riwayat pesanan agar status 'pending' terlihat
                        setTimeout(() => window.location.href = '{{ route("user.pesanan") }}', 2000);
                    }
                });
            }
        })
        .catch(error => {
            showNotification(error.message, 'error');
            resetButton();
        });
    });

    function resetButton() {
        buttonText.classList.remove('hidden');
        buttonSpinner.classList.add('hidden');
        payButton.disabled = false;
    }
});
</script>
@endsection