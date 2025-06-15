<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('layouts.admindashboard', compact('user'));
    }

    public function dashboard()
    {
        // Ambil produk terlaris berdasarkan jumlah pesanan bulan ini  
        $produkTerlaris = DB::table('pesanans')
            ->join('produks', 'pesanans.produk_id', '=', 'produks.id')
            ->select(
                'produks.id',
                'produks.nama_produk',
                'produks.gambar_produk',
                DB::raw('SUM(pesanans.jumlah) as total_terjual')
            )
            ->whereMonth('pesanans.created_at', now()->month)
            ->whereYear('pesanans.created_at', now()->year)
            ->where('pesanans.status', '!=', 'cancelled') // Exclude cancelled orders
            ->groupBy('produks.id', 'produks.nama_produk', 'produks.gambar_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        // Dapatkan nama bulan saat ini dari pesanan
        $currentMonth = $this->getCurrentMonthName();

        return view('admin.dashboard', compact('produkTerlaris', 'currentMonth'));
    }

    // Method untuk mendapatkan nama bulan dalam bahasa Indonesia
    private function getCurrentMonthName()
    {
        // Cek apakah ada pesanan di bulan ini berdasarkan created_at
        $latestOrder = DB::table('pesanans')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestOrder) {
            // Ambil bulan dari pesanan terbaru
            $monthNumber = Carbon::parse($latestOrder->created_at)->month;
        } else {
            // Jika tidak ada pesanan bulan ini, gunakan bulan sekarang
            $monthNumber = now()->month;
        }

        return $this->getIndonesianMonthName($monthNumber);
    }

    // Method untuk mengkonversi nomor bulan ke nama bulan Indonesia
    private function getIndonesianMonthName($monthNumber)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $months[$monthNumber] ?? 'Bulan tidak valid';
    }

    // Method untuk AJAX request filter periode
    public function getProdukTerlaris(Request $request)
    {
        $periode = $request->get('periode', 'monthly');
        
        $query = DB::table('pesanans')
            ->join('produks', 'pesanans.produk_id', '=', 'produks.id')
            ->select(
                'produks.id',
                'produks.nama_produk',
                'produks.gambar_produk',
                DB::raw('SUM(pesanans.jumlah) as total_terjual')
            )
            ->where('pesanans.status', '!=', 'cancelled'); // Exclude cancelled orders

        if ($periode === 'weekly') {
            $query->whereBetween('pesanans.created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($periode === 'daily') {
            $query->whereDate('pesanans.created_at', now()->toDateString());
        } else {
            // Default monthly
            $query->whereMonth('pesanans.created_at', now()->month)
                  ->whereYear('pesanans.created_at', now()->year);
        }

        $produkTerlaris = $query->groupBy('produks.id', 'produks.nama_produk', 'produks.gambar_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        // Tentukan nama periode untuk response
        $currentPeriod = '';
        if ($periode === 'monthly') {
            $currentPeriod = $this->getCurrentMonthName();
        } elseif ($periode === 'weekly') {
            $currentPeriod = 'Minggu sekarang';
        } elseif ($periode === 'daily') {
            $currentPeriod = 'Hari ini';
        }

        return response()->json([
            'products' => $produkTerlaris,
            'currentPeriod' => $currentPeriod
        ]);
    }

    // Method tambahan untuk mendapatkan statistik dashboard
    public function getStatistik()
    {
        $today = now();
        
        // Total pesanan hari ini
        $pesananHariIni = DB::table('pesanans')
            ->whereDate('created_at', $today->toDateString())
            ->count();
        
        // Total pesanan bulan ini
        $pesananBulanIni = DB::table('pesanans')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();
        
        // Total pendapatan bulan ini
        $pendapatanBulanIni = DB::table('pesanans')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->where('status', 'complete')
            ->sum('total_harga');
        
        // Pesanan pending
        $pesananPending = DB::table('pesanans')
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'pesanan_hari_ini' => $pesananHariIni,
            'pesanan_bulan_ini' => $pesananBulanIni,
            'pendapatan_bulan_ini' => $pendapatanBulanIni,
            'pesanan_pending' => $pesananPending
        ]);
    }
}