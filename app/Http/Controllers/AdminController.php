<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('layouts.admindashboard', compact('user'));
    }

    private function getDailyStats()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data = DB::table('pesanans')
            ->selectRaw('DATE(created_at) as tanggal')
            ->selectRaw('SUM(CASE WHEN status != "cancelled" THEN total_harga ELSE 0 END) as pendapatan')
            ->selectRaw('COUNT(id) as jumlah_pesanan') // Menggunakan COUNT(id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return $data;
    }

    public function dashboard()
    {
        // Ambil produk terlaris berdasarkan jumlah pesanan bulan ini
    $produkTerlaris = DB::table('detail_pesanans')
        ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
        ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
        ->select(
            'produks.id',
            'produks.nama_produk',
            'produks.gambar_produk',
            DB::raw('SUM(detail_pesanans.jumlah) as total_terjual')
        )
        ->whereMonth('pesanans.created_at', now()->month)
        ->whereYear('pesanans.created_at', now()->year)
        ->where('pesanans.status', '!=', 'cancelled')
        ->groupBy('produks.id', 'produks.nama_produk', 'produks.gambar_produk')
        ->orderBy('total_terjual', 'desc')
        ->limit(10)
        ->get();

    $currentMonth = $this->getCurrentMonthName();
    $stats = $this->getDashboardStats();

    // Data untuk Chart.js
    $dailyStats = $this->getDailyStats();

    return view('admin.dashboard', compact('produkTerlaris', 'currentMonth', 'stats'))->with('dailyStats', json_encode($dailyStats));
    }

    // Method untuk mendapatkan statistik dashboard
    private function getDashboardStats()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // 1. Total Pendapatan - dari semua pesanan bulan ini kecuali yang dibatalkan
        $totalPendapatan = DB::table('pesanans')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->sum('total_harga');

        // 2. Total Pelanggan - hitung jumlah user unik yang pernah memesan bulan ini
        $totalPelanggan = DB::table('pesanans')
            ->join('users', 'pesanans.user_id', '=', 'users.id')
            ->whereMonth('pesanans.created_at', $currentMonth)
            ->whereYear('pesanans.created_at', $currentYear)
            ->where('pesanans.status', '!=', 'cancelled')
            ->distinct('users.id')
            ->count('users.id');

        // 3. Total Produk - dari semua produk yang ada
        $totalProduk = DB::table('produks')->count();

        // 4. Pesanan Dikirim - pesanan dengan status 'dikirim' bulan ini
        $pesananDikirim = DB::table('pesanans')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'dikirim')
            ->count();

        // 5. Pesanan Dibatalkan - pesanan dengan status 'cancelled' bulan ini
        $pesananDibatalkan = DB::table('pesanans')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'cancelled')
            ->count();

        // 6. Total Pesanan - semua pesanan bulan ini (termasuk yang dibatalkan)
        $totalPesanan = DB::table('pesanans')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return [
            'total_pendapatan' => $totalPendapatan,
            'total_pelanggan' => $totalPelanggan,
            'total_produk' => $totalProduk,
            'pesanan_dikirim' => $pesananDikirim,
            'pesanan_dibatalkan' => $pesananDibatalkan,
            'total_pesanan' => $totalPesanan
        ];
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

        $query = DB::table('detail_pesanans')
            ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->select(
                'produks.id',
                'produks.nama_produk',
                'produks.gambar_produk',
                DB::raw('SUM(detail_pesanans.jumlah) as total_terjual')
            )
            ->where('pesanans.status', '!=', 'cancelled');

        if ($periode === 'weekly') {
            $query->whereBetween('pesanans.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $currentPeriod = 'Minggu ini';
        } elseif ($periode === 'daily') {
            $query->whereDate('pesanans.created_at', now()->toDateString());
            $currentPeriod = 'Hari ini';
        } else { // monthly
            $query->whereMonth('pesanans.created_at', now()->month)
                ->whereYear('pesanans.created_at', now()->year);
            $currentPeriod = $this->getCurrentMonthName();
        }

        $produkTerlaris = $query->groupBy('produks.id', 'produks.nama_produk', 'produks.gambar_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'products' => $produkTerlaris,
            'currentPeriod' => $currentPeriod
        ]);
    }

    // Method tambahan untuk mendapatkan statistik dashboard (untuk AJAX jika diperlukan)
    public function getStatistik()
    {
        $stats = $this->getDashboardStats();
        return response()->json($stats);
    }

    // Di AdminController.php
    public function cancelledPesanans()
    {
        $pesanans = Pesanan::with(['user', 'produk'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('pesanans.cancelled', compact('pesanans'));
    }

    public function restorePesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'pending']);

        return redirect()->back()->with('success', 'Pesanan berhasil dikembalikan ke status pending');
    }

    public function forceDeletePesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dihapus secara permanen');
    }
}
