<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Alamat;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PesananController extends Controller
{
    // Halaman sukses pesanan
    public function success(Request $request, $id)
    {
        $paymentStatus = $request->query('status', 'success');
        $pesanan = Pesanan::with(['details.produk', 'promo', 'user'])->findOrFail($id);

        return view('pesanans.success', compact('pesanan', 'paymentStatus'));
    }

    // Index untuk admin (daftar pesanan aktif saja - TIDAK termasuk cancelled)
    public function index()
    {
        $pesanans = Pesanan::with(['user', 'details.produk'])
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pesanans.index', compact('pesanans'));
    }

    // Daftar pesanan yang dibatalkan (untuk admin)
    public function cancelled()
    {
        $pesanans = Pesanan::with(['user', 'details.produk'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pesanans.cancelled', compact('pesanans'));
    }

    // Update status pesanan (untuk admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,dikirim,complete,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $pesanan = Pesanan::with('details.produk')->findOrFail($id);
            $statusLama = $pesanan->status;
            $statusBaru = $request->status;

            // Jika status berubah dari non-cancelled ke cancelled, kembalikan stok
            if ($statusLama !== 'cancelled' && $statusBaru === 'cancelled') {
                foreach ($pesanan->details as $detail) {
                    if ($detail->produk) {
                        $detail->produk->increment('stok_produk', $detail->jumlah);
                    }
                }
            }

            // Jika status berubah dari cancelled ke non-cancelled, kurangi stok
            if ($statusLama === 'cancelled' && $statusBaru !== 'cancelled') {
                // Cek stok terlebih dahulu untuk semua produk
                foreach ($pesanan->details as $detail) {
                    if ($detail->produk && $detail->produk->stok_produk < $detail->jumlah) {
                        DB::rollback();
                        return back()->with('error', 'Stok produk "' . $detail->produk->nama_produk . '" tidak mencukupi untuk mengaktifkan kembali pesanan ini!');
                    }
                }
                
                // Jika semua stok mencukupi, kurangi stok
                foreach ($pesanan->details as $detail) {
                    if ($detail->produk) {
                        $detail->produk->decrement('stok_produk', $detail->jumlah);
                    }
                }
            }

            // Update status
            $pesanan->update(['status' => $statusBaru]);

            DB::commit();

            // Redirect yang tepat berdasarkan status baru
            if ($statusBaru === 'cancelled') {
                return redirect()->route('admin.pesanans.index')
                    ->with('success', 'Pesanan berhasil dibatalkan dan dipindahkan ke daftar pesanan yang dibatalkan!');
            } else {
                return back()->with('success', 'Status pesanan berhasil diupdate!');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk restore pesanan yang dibatalkan
    public function restore($id)
    {
        $pesanan = Pesanan::with('details.produk')->findOrFail($id);

        // Cek apakah pesanan memang dalam status cancelled
        if ($pesanan->status !== 'cancelled') {
            return back()->with('error', 'Pesanan ini tidak dalam status dibatalkan!');
        }

        // Cek apakah stok produk masih mencukupi untuk semua item
        foreach ($pesanan->details as $detail) {
            if ($detail->produk && $detail->produk->stok_produk < $detail->jumlah) {
                return back()->with('error', 'Stok produk "' . $detail->produk->nama_produk . '" tidak mencukupi untuk mengembalikan pesanan ini!');
            }
        }

        try {
            DB::beginTransaction();

            // Update status ke pending
            $pesanan->update(['status' => 'pending']);

            // Kurangi stok produk kembali untuk semua item
            foreach ($pesanan->details as $detail) {
                if ($detail->produk) {
                    $detail->produk->decrement('stok_produk', $detail->jumlah);
                }
            }

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dipulihkan dan dikembalikan ke status pending!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk menghapus pesanan secara permanen
    public function forceDelete($id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);

        // Hanya bisa menghapus pesanan yang statusnya cancelled
        if ($pesanan->status !== 'cancelled') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang bisa dihapus permanen!');
        }

        try {
            DB::beginTransaction();

            // Hapus detail pesanan terlebih dahulu
            $pesanan->details()->delete();
            
            // Hapus pesanan
            $pesanan->delete();

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dihapus secara permanen!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk user melihat daftar pesanan mereka
    public function pesanan(Request $request)
    {
        $query = Auth::user()->pesanans()->with('details.produk', 'promo')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->paginate(10);

        return view('user.pesanan', compact('pesanans'));
    }

    // Method untuk user membatalkan pesanan
    public function cancelByUser(Pesanan $pesanan)
    {
        // 1. Otorisasi: Pastikan pesanan ini milik pengguna yang sedang login
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        // 2. Validasi: Hanya pesanan dengan status 'pending' yang bisa dibatalkan
        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Pesanan ini sudah diproses dan tidak dapat dibatalkan.');
        }

        try {
            // 3. Mulai Transaksi Database untuk menjaga konsistensi data
            DB::transaction(function () use ($pesanan) {
                // 4. Kembalikan stok untuk setiap item detail pesanan
                foreach ($pesanan->details as $detail) {
                    if ($detail->produk) {
                        $detail->produk->increment('stok_produk', $detail->jumlah);
                    }
                }

                // 5. Ubah status pesanan menjadi 'cancelled'
                $pesanan->update(['status' => 'cancelled']);
            });

            return redirect()->route('user.pesanan')->with('success', 'Pesanan dengan kode ' . $pesanan->kode_pesanan . ' berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.');
        }
    }

    // Method untuk mengambil data detail pesanan via AJAX
    public function showAjax(Pesanan $pesanan)
    {
        // Otorisasi: Pastikan pesanan ini milik pengguna yang sedang login.
        if ($pesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        // Memuat relasi yang dibutuhkan agar data produk ikut terkirim.
        $pesanan->load('details.produk', 'promo');

        // Mengembalikan data dalam format JSON yang siap digunakan oleh JavaScript.
        return response()->json([
            'success' => true,
            'data' => [
                'kode_pesanan' => $pesanan->kode_pesanan,
                'tanggal' => $pesanan->created_at->isoFormat('D MMMM YYYY, HH:mm'),
                'status' => $pesanan->status,
                'metode_pengiriman' => $pesanan->metode_pengiriman,
                'metode_pembayaran' => $pesanan->metode_pembayaran,
                'alamat_pengiriman' => $pesanan->alamat_pengiriman,
                'catatan' => $pesanan->catatan ?? '-',
                'subtotal' => 'Rp ' . number_format($pesanan->details->sum('subtotal'), 0, ',', '.'),
                'ongkos_kirim' => 'Rp ' . number_format($pesanan->ongkos_kirim, 0, ',', '.'),
                'diskon' => '- Rp ' . number_format($pesanan->diskon, 0, ',', '.'),
                'total_harga' => 'Rp ' . number_format($pesanan->total_harga, 0, ',', '.'),
                'promo' => $pesanan->promo ? [
                    'kode' => $pesanan->promo->kode,
                    'nama' => $pesanan->promo->nama,
                    'diskon' => $pesanan->promo->diskon
                ] : null,
                'items' => $pesanan->details->map(function ($detail) {
                    return [
                        'nama_produk' => optional($detail->produk)->nama_produk ?? 'Produk Dihapus',
                        'gambar_url' => optional($detail->produk)->gambar_produk ? asset('storage/' . $detail->produk->gambar_produk) : 'https://via.placeholder.com/150',
                        'jumlah' => $detail->jumlah,
                        'harga_satuan' => 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.'),
                        'subtotal' => 'Rp ' . number_format($detail->subtotal, 0, ',', '.'),
                    ];
                }),
            ]
        ]);
    }

    // Method untuk admin melihat detail pesanan
    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'details.produk', 'promo'])->findOrFail($id);
        
        return view('pesanans.show', compact('pesanan'));
    }

    // Method untuk menampilkan statistik pesanan (untuk admin)
    public function statistics()
    {
        $totalPesanan = Pesanan::count();
        $totalPending = Pesanan::where('status', 'pending')->count();
        $totalProses = Pesanan::where('status', 'proses')->count();
        $totalDikirim = Pesanan::where('status', 'dikirim')->count();
        $totalComplete = Pesanan::where('status', 'complete')->count();
        $totalCancelled = Pesanan::where('status', 'cancelled')->count();
        
        $totalRevenue = Pesanan::where('status', 'complete')->sum('total_harga');
        $revenueToday = Pesanan::where('status', 'complete')
            ->whereDate('updated_at', today())
            ->sum('total_harga');
        
        $revenueThisMonth = Pesanan::where('status', 'complete')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total_harga');

        return view('pesanans.statistics', compact(
            'totalPesanan', 'totalPending', 'totalProses', 'totalDikirim', 
            'totalComplete', 'totalCancelled', 'totalRevenue', 
            'revenueToday', 'revenueThisMonth'
        ));
    }

    // Method untuk filter pesanan berdasarkan tanggal (untuk admin)
    public function filterByDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:pending,proses,dikirim,complete,cancelled'
        ]);

        $query = Pesanan::with(['user', 'details.produk'])
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->paginate(10)->withQueryString();

        return view('pesanans.index', compact('pesanans'));
    }

    // Method untuk export pesanan ke Excel/CSV (optional)
    public function export(Request $request)
    {
        // Implementasi export jika diperlukan
        // Bisa menggunakan package seperti Laravel Excel
    }

        public function notif()
    {
        // Ambil 5 testimoni terbaru
        $pesananBaru = Pesanan::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('pesananBaru'));
    }
}