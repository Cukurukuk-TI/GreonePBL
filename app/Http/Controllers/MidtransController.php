<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    /**
     * Method ini akan menangani notifikasi pembayaran dari Midtrans.
     */
    public function notificationHandler(Request $request)
    {
        // 1. Set konfigurasi server key Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        // 2. Buat instance notifikasi Midtrans
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification'], 400);
        }

        // 3. Ambil data penting dari notifikasi
        $status = $notification->transaction_status;
        $orderIdMidtrans = $notification->order_id;

        // Ambil ID pesanan asli dari order_id Midtrans (misal: dari "BGD-123-timestamp" menjadi "123")
        $orderIdParts = explode('-', $orderIdMidtrans);
        $pesananId = $orderIdParts[1]; // Mengambil bagian kedua

        // 4. Cari pesanan di database Anda
        $pesanan = Pesanan::find($pesananId);

        if (!$pesanan) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }

        // 5. Lakukan verifikasi signature key (keamanan)
        $signatureKey = hash('sha512', $orderIdMidtrans . $status . $notification->gross_amount . config('midtrans.server_key'));
        if ($notification->signature_key != $signatureKey) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // 6. Update status pesanan berdasarkan notifikasi
        if ($status == 'capture' || $status == 'settlement') {
            // Jika pembayaran berhasil (untuk kartu kredit 'capture', selain itu 'settlement')
            $pesanan->status_pesanan = 'diproses'; // Atau 'dibayar', 'dikonfirmasi', dll.
        } else if ($status == 'pending') {
            // Pembayaran masih menunggu (misal: transfer bank, gerai)
            $pesanan->status_pesanan = 'pending';
        } else if ($status == 'deny' || $status == 'expire' || $status == 'cancel') {
            // Pembayaran gagal, kadaluwarsa, atau dibatalkan
            $pesanan->status_pesanan = 'dibatalkan';
        }

        // Simpan perubahan status ke database
        $pesanan->save();

        // 7. Beri respon OK (200) ke Midtrans untuk menandakan notifikasi berhasil diterima
        return response()->json(['message' => 'Notification successfully handled']);
    }
}
