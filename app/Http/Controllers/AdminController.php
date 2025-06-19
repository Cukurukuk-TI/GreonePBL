<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
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
        // PERBAIKAN: Query produk terlaris sekarang dari 'detail_pesanans'
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

        return view('admin.dashboard', compact('produkTerlaris', 'currentMonth', 'stats'));
    }

    private function getDashboardStats()
    {
        // ... (Kode untuk statistik tidak perlu diubah signifikan, biarkan sama seperti file asli Anda)
        // ... Namun, untuk konsistensi, lebih baik menggunakan Eloquent seperti ini:
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalPendapatan = Pesanan::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->sum('total_harga');

        $totalPelanggan = User::where('role', 'user')->count();
        $totalProduk = Produk::count();

        $pesananDikirim = Pesanan::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'dikirim')
            ->count();

        $pesananDibatalkan = Pesanan::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'cancelled')
            ->count();

        $totalPesanan = Pesanan::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return compact('totalPendapatan', 'totalPelanggan', 'totalProduk', 'pesananDikirim', 'pesananDibatalkan', 'totalPesanan');
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
