<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // 1. Atur konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Gagal membuat instance Notifikasi Midtrans: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        Log::info("Notifikasi Midtrans diterima untuk kode_pesanan: {$orderId} dengan status: {$transactionStatus}");

        // --- CARI PESANAN BERDASARKAN KODE PESANAN ---
        $pesanan = Pesanan::with('details.produk')->where('kode_pesanan', $orderId)->first();

        if (!$pesanan) {
            Log::warning("Pesanan dengan Kode: {$orderId} tidak ditemukan.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Jangan proses jika status sudah final (selesai, diproses, atau dibatalkan)
        if (in_array($pesanan->status, ['diproses', 'selesai', 'dibatalkan'])) {
            Log::info("Pesanan {$orderId} sudah dalam status final, notifikasi diabaikan.");
            return response()->json(['message' => 'Notification ignored for final order status.']);
        }

        // Gunakan DB::transaction untuk memastikan semua operasi berhasil
        return DB::transaction(function () use ($transactionStatus, $fraudStatus, $pesanan) {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept') {
                    // --- LOGIKA BARU ---
                    // 1. Update status pesanan ke 'diproses'
                    $pesanan->status = 'diproses';
                    
                    // 2. Kurangi stok produk
                    foreach ($pesanan->details as $detail) {
                        if ($detail->produk) {
                            $detail->produk->decrement('stok_produk', $detail->jumlah);
                            Log::info("Stok untuk produk ID: {$detail->produk->id} dikurangi sebanyak {$detail->jumlah}.");
                        }
                    }
                }
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                // --- LOGIKA BARU ---
                // Ubah status ke 'dibatalkan'. Stok tidak perlu dikembalikan karena belum dikurangi.
                $pesanan->status = 'dibatalkan';
            }

            // Simpan perubahan ke database
            $pesanan->save();

            Log::info("Status pesanan {$pesanan->kode_pesanan} berhasil diupdate menjadi: {$pesanan->status}");
            return response()->json(['message' => 'Notification successfully processed.']);
        });
    }

    public function handle(Request $request)
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            // Buat instance dari notifikasi Midtrans
            $notification = new Notification();

            // Ambil status transaksi dan kode pesanan
            $status = $notification->transaction_status;
            $orderCode = $notification->order_id;

            // Cari pesanan berdasarkan kode pesanan
            $pesanan = Pesanan::where('kode_pesanan', $orderCode)->first();

            // Lakukan verifikasi signature key (opsional tapi sangat direkomendasikan)
            $signatureKey = hash('sha512', $orderCode . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
            if ($signatureKey != $notification->signature_key) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Jika pesanan ditemukan, update statusnya
            if ($pesanan) {
                if ($status == 'capture' || $status == 'settlement') {
                    // Hanya jika pembayaran berhasil
                    if ($pesanan->status == 'pending') {
                         DB::transaction(function() use ($pesanan) {
                            // 1. Update status pesanan
                            $pesanan->update(['status' => 'paid']);

                            // 2. Kurangi stok produk
                            foreach ($pesanan->details as $item) {
                                Produk::find($item->produk_id)->decrement('stok_produk', $item->jumlah);
                            }
                        });
                    }
                } elseif ($status == 'expire') {
                    $pesanan->update(['status' => 'expired']);
                } elseif ($status == 'cancel' || $status == 'deny') {
                    $pesanan->update(['status' => 'cancelled']);
                }
            }
            
            return response()->json(['message' => 'Notification successfully processed']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
