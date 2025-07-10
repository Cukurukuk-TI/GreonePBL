<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Metode utama untuk menampilkan halaman dashboard
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $stats = [
            'total_pendapatan' => Pesanan::where('status', 'complete')->sum('total_harga') ?? 0,
            'total_pelanggan' => User::count() ?? 0,
            'total_produk' => Produk::count() ?? 0,
            'pesanan_dikirim' => Pesanan::where('status', 'dikirim')->count() ?? 0,
            'pesanan_dibatalkan' => Pesanan::where('status', 'cancelled')->count() ?? 0,
            'total_pesanan' => Pesanan::count() ?? 0,
        ];

        $defaultMonth = now()->month;
        $defaultYear = now()->year;

        $grafikDataPendapatan = $this->getDynamicChartData('pendapatan', 'monthly', $defaultMonth, $defaultYear);
        $grafikDataPesanan = $this->getDynamicChartData('pesanan', 'monthly', $defaultMonth, $defaultYear);
        
        $produkTerlarisData = $this->getProdukData('monthly', $defaultMonth, $defaultYear);
        $produkTerlaris = $produkTerlarisData['products'];
        $currentPeriodName = $produkTerlarisData['currentPeriod'];
        
        return view('admin.dashboard', compact(
            'produkTerlaris', 
            'stats', 
            'currentPeriodName', 
            'grafikDataPesanan', 
            'grafikDataPendapatan'
        ));
    }
    
    // --- Helper Function untuk Kalkulasi Minggu ---
    private function calculateWeeklyPeriod(string $weekNumber, int $month, int $year): array
    {
        if ($weekNumber === 'current') {
            $baseDate = Carbon::now();
        } else {
            $baseDate = Carbon::createFromDate($year, $month, 1);
        }

        $monthStart = $baseDate->copy()->startOfMonth();
        $monthEnd = $baseDate->copy()->endOfMonth();

        $weekForCalc = ($weekNumber === 'current')
            ? floor(($baseDate->day - 1) / 7) + 1
            : (int)$weekNumber;
        
        $startDay = ($weekForCalc - 1) * 7;
        $startDate = $monthStart->copy()->addDays($startDay);
        
        if ($startDate->gt($monthEnd)) return [null, null];
        
        $endDate = $startDate->copy()->addDays(6)->min($monthEnd);
        
        return [$startDate, $endDate];
    }

    // --- Helper Function untuk Data Grafik Dinamis ---
    private function getDynamicChartData(string $type, string $periode, ?string $timeUnit, ?int $monthForContext)
    {
        $year = now()->year;
        $labelFormat = 'd M';
        
        if ($periode === 'monthly') {
            $month = (int)($timeUnit ?? now()->month);
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
        } else { // weekly
            $week = $timeUnit ?? 'current';
            $month = $monthForContext ?? now()->month;
            list($startDate, $endDate) = $this->calculateWeeklyPeriod($week, $month, $year);
            $labelFormat = 'D, d M'; 
        }

        if (!$startDate || $startDate->gt($endDate)) {
            return ['labels' => []];
        }

        $labels = [];
        $dailyData = Pesanan::selectRaw('
                DATE(created_at) as date,
                SUM(CASE WHEN status = "complete" THEN total_harga ELSE 0 END) as daily_revenue,
                COUNT(*) as total,
                COUNT(CASE WHEN status = "complete" THEN 1 END) as completed,
                COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled
            ')
            ->whereBetween('created_at', [$startDate, $endDate])->groupBy('date')->get()->keyBy('date');
        
        $pendapatanData = []; $totalOrders = []; $completedOrders = []; $cancelledOrders = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $labels[] = $date->translatedFormat($labelFormat);
            $dayData = $dailyData->get($date->toDateString());
            if ($type === 'pendapatan') {
                $pendapatanData[] = $dayData->daily_revenue ?? 0;
            } else {
                $totalOrders[] = $dayData->total ?? 0;
                $completedOrders[] = $dayData->completed ?? 0;
                $cancelledOrders[] = $dayData->cancelled ?? 0;
            }
        }
        if ($type === 'pendapatan') return ['labels' => $labels, 'pendapatan' => $pendapatanData];
        return ['labels' => $labels, 'total_orders' => $totalOrders, 'completed_orders' => $completedOrders, 'cancelled_orders' => $cancelledOrders];
    }

    // --- Helper Function untuk Data Produk Terlaris ---
    private function getProdukData(string $periode, ?string $timeUnit, ?int $monthForContext)
    {
        $year = now()->year;
        $startDate = null; $endDate = null; $currentPeriodText = '';

        if ($periode === 'monthly') {
            $month = (int)($timeUnit ?? now()->month);
            $monthDate = Carbon::create($year, $month, 1);
            $startDate = $monthDate->copy()->startOfMonth();
            $endDate = $monthDate->copy()->endOfMonth();
            $currentPeriodText = $monthDate->translatedFormat('F Y');
        } else { // weekly
            $week = $timeUnit ?? 'current';
            $month = $monthForContext ?? now()->month;
            list($startDate, $endDate) = $this->calculateWeeklyPeriod($week, $month, $year);
            $currentPeriodText = 'Minggu ke-' . ($week === 'current' ? (floor((now()->day-1)/7)+1) : $week) . ' ' . Carbon::create(null, $month, 1)->translatedFormat('F Y');
        }

        $produkTerlaris = collect([]);
        if ($startDate && !$startDate->gt($endDate)) {
            $produkTerlaris = DB::table('detail_pesanans')
                ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
                ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
                ->select('produks.id', 'produks.nama_produk', 'produks.gambar_produk', DB::raw('SUM(detail_pesanans.jumlah) as total_terjual'))
                ->whereBetween('pesanans.created_at', [$startDate, $endDate])
                ->where('pesanans.status', 'complete')
                ->groupBy('produks.id', 'produks.nama_produk', 'produks.gambar_produk')
                ->orderBy('total_terjual', 'desc')->limit(10)->get();
        }
        return ['products' => $produkTerlaris, 'currentPeriod' => $currentPeriodText];
    }
    
    // --- Metode yang dipanggil oleh Route AJAX ---
    public function ajaxPendapatan(Request $request) {
        $data = $this->getDynamicChartData('pendapatan', $request->query('periode'), $request->query('time_unit'), $request->query('month_context'));
        return response()->json($data);
    }
    public function ajaxPesanan(Request $request) {
        $data = $this->getDynamicChartData('pesanan', $request->query('periode'), $request->query('time_unit'), $request->query('month_context'));
        return response()->json($data);
    }
    public function ajaxProdukTerlaris(Request $request) {
        $data = $this->getProdukData($request->query('periode'), $request->query('time_unit'), $request->query('month_context'));
        return response()->json($data);
    }
}