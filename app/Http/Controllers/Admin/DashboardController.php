<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil 5 produk terlaris berdasarkan total jumlah pesanan
        $produkTerlaris = Produk::select('produk.*')
            ->selectRaw('COALESCE(SUM(pesanans.jumlah), 0) as total_terjual')
            ->leftJoin('pesanans', 'produk.id', '=', 'pesanans.produk_id')
            ->where(function($query) {
                $query->whereNull('pesanans.id')
                      ->orWhere('pesanans.status', '!=', 'cancelled'); // Mengecualikan pesanan yang dibatalkan jika ada
            })
            ->groupBy('produk.id', 'produk.nama_produk', 'produk.gambar_produk', 'produk.harga', 'produk.stok', 'produk.deskripsi', 'produk.created_at', 'produk.updated_at')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('produkTerlaris'));
    }
}