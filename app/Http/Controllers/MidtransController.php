<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // 1. Atur konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            // 2. Buat instance notifikasi dari data JSON yang dikirim Midtrans
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Gagal membuat instance Notifikasi Midtrans: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        // 3. Ambil ID pesanan dan status transaksi
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        // Log notifikasi untuk debugging
        Log::info("Notifikasi Midtrans diterima untuk order_id: {$orderId} dengan status: {$transactionStatus}");

        // 4. Cari pesanan di database Anda
        $order = Pesanan::find($orderId);

        if (!$order) {
            Log::warning("Pesanan dengan ID: {$orderId} tidak ditemukan.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Jangan proses jika status pesanan sudah final (selesai/dibatalkan)
        if ($order->status === 'completed' || $order->status === 'cancelled') {
            Log::info("Pesanan {$orderId} sudah dalam status final, notifikasi diabaikan.");
            return response()->json(['message' => 'Notification ignored for final order status.']);
        }

        // 5. Update status pesanan berdasarkan notifikasi
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // Untuk kartu kredit, transaksi berhasil dan aman
                $order->status = 'processed';
            }
        } else if ($transactionStatus == 'settlement') {
            // Untuk metode lain, transaksi berhasil
            $order->status = 'processed';
        } else if ($transactionStatus == 'pending') {
            // Transaksi masih menunggu pembayaran
            $order->status = 'pending';
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            // Transaksi gagal, kedaluwarsa, atau dibatalkan
            $order->status = 'cancelled';
        }

        // 6. Simpan perubahan status ke database
        $order->save();

        Log::info("Status pesanan {$orderId} berhasil diupdate menjadi: {$order->status}");

        return response()->json(['message' => 'Notification successfully processed.']);
    }
}
