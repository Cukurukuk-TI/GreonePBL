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
        // Set konfigurasi server key Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification'], 400);
        }

        $status = $notification->transaction_status;
        $orderIdMidtrans = $notification->order_id;
        $orderIdParts = explode('-', $orderIdMidtrans);
        $pesananId = $orderIdParts[1];

        // Cari pesanan di database dengan memuat relasi detail dan produknya
        $pesanan = Pesanan::with('detailPesanans.produk')->find($pesananId);

        if (!$pesanan) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }

        // Lakukan verifikasi signature key
        $signatureKey = hash('sha512', $orderIdMidtrans . $status . $notification->gross_amount . config('midtrans.server_key'));
        if ($notification->signature_key != $signatureKey) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Update status pesanan dan kelola stok
        if ($status == 'capture' || $status == 'settlement') {
            // Cek agar tidak memproses status yang sama berulang kali
            if ($pesanan->status_pesanan == 'pending') {
                $pesanan->status_pesanan = 'diproses';
                $pesanan->save();

                // Kurangi stok produk
                foreach ($pesanan->detailPesanans as $detail) {
                    $detail->produk->decrement('stok_produk', $detail->jumlah);
                }
            }
        } else if ($status == 'pending') {
            $pesanan->status_pesanan = 'pending';
            $pesanan->save();
        } else if ($status == 'deny' || $status == 'expire' || $status == 'cancel') {
            // Cek agar tidak memproses status yang sama berulang kali
            if ($pesanan->status_pesanan == 'pending') {
                $pesanan->status_pesanan = 'dibatalkan';
                $pesanan->save();

                // Kembalikan stok produk (opsional tapi sangat direkomendasikan)
                // foreach ($pesanan->detailPesanans as $detail) {
                //     $detail->produk->increment('stok_produk', $detail->jumlah);
                // }
            }
        }

        return response()->json(['message' => 'Notification successfully handled']);
    }

}
