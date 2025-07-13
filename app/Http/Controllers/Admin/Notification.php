<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Notification extends Controller
{
 public function markAsRead(Request $request)
    {
        try {
            // Ambil notification key dari request
            $notificationKey = $request->input('notification_key');
            
            if ($notificationKey) {
                // Simpan status notifikasi sudah dibaca ke session dengan key spesifik
                Session::put($notificationKey, true);
                Session::put($notificationKey . '_read_at', now());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sebagai sudah dibaca'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}