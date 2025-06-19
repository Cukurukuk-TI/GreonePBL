<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Alamat;
use App\Models\DetailPesanan; // Ditambahkan karena kita akan menggunakannya
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PesananController extends Controller
{
    // ===================================================================================
    // Alur Pembelian Produk Tunggal (Lama & Dinonaktifkan)
    // Method create() dan store() sengaja dinonaktifkan untuk menghindari error,
    // karena alur utama aplikasi sekarang adalah melalui KeranjangController.
    // Jika ingin diaktifkan kembali, logikanya harus diubah total untuk membuat DetailPesanan.
    // ===================================================================================
    public function create(Request $request, $produkId)
    {
        return redirect()->route('home')->with('error', 'Fitur ini sedang dinonaktifkan. Silakan gunakan keranjang belanja.');
    }

    public function store(Request $request)
    {
        // Logika di sini sudah tidak valid karena struktur database berubah. Dinonaktifkan.
        return redirect()->route('home')->with('error', 'Fitur ini sedang dinonaktifkan.');
    }
    // ===================================================================================


    // Halaman sukses pesanan (setelah checkout dari Keranjang)
    public function success($id)
    {
        // PERBAIKAN: Tidak perlu lagi 'produk' atau 'promo' di sini karena info sudah di detail.
        $pesanan = Pesanan::findOrFail($id);
        return view('pesanans.success', compact('pesanan'));
    }

    // Index untuk admin (daftar pesanan aktif)
    public function index()
    {
        // PERBAIKAN: Ganti with('produk') menjadi with('detailPesanans.produk')
        $pesanans = Pesanan::with(['user', 'detailPesanans.produk'])
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pesanans.index', compact('pesanans'));
    }

    // Daftar pesanan yang dibatalkan (untuk admin)
    public function cancelled()
    {
        // PERBAIKAN: Ganti with('produk') menjadi with('detailPesanans.produk')
        $pesanans = Pesanan::with(['user', 'detailPesanans.produk'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();

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

            // PERBAIKAN: Muat relasi detailPesanans agar bisa diakses untuk manajemen stok
            $pesanan = Pesanan::with('detailPesanans.produk')->findOrFail($id);
            $statusLama = $pesanan->status;
            $statusBaru = $request->status;

            // PERBAIKAN LOGIKA STOK: Loop melalui setiap detail pesanan
            if ($statusLama !== 'cancelled' && $statusBaru === 'cancelled') {
                foreach ($pesanan->detailPesanans as $detail) {
                    $detail->produk->increment('stok_produk', $detail->jumlah);
                }
            }

            if ($statusLama === 'cancelled' && $statusBaru !== 'cancelled') {
                foreach ($pesanan->detailPesanans as $detail) {
                    if ($detail->produk->stok_produk < $detail->jumlah) {
                        // Batalkan transaksi jika salah satu stok produk tidak cukup
                        DB::rollBack();
                        return back()->with('error', 'Stok produk ' . $detail->produk->nama_produk . ' tidak mencukupi!');
                    }
                    $detail->produk->decrement('stok_produk', $detail->jumlah);
                }
            }

            $pesanan->update(['status' => $statusBaru]);
            DB::commit();

            if ($statusBaru === 'cancelled') {
                return redirect()->route('admin.pesanans.index')
                    ->with('success', 'Pesanan berhasil dibatalkan!');
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
        // PERBAIKAN: Muat relasi detailPesanans
        $pesanan = Pesanan::with('detailPesanans.produk')->findOrFail($id);

        if ($pesanan->status !== 'cancelled') {
            return back()->with('error', 'Pesanan ini tidak dalam status dibatalkan!');
        }

        try {
            DB::beginTransaction();

            // PERBAIKAN LOGIKA STOK: Loop melalui setiap detail pesanan
            foreach ($pesanan->detailPesanans as $detail) {
                if ($detail->produk->stok_produk < $detail->jumlah) {
                    DB::rollBack();
                    return back()->with('error', 'Stok produk ' . $detail->produk->nama_produk . ' tidak mencukupi untuk memulihkan pesanan ini!');
                }
                $detail->produk->decrement('stok_produk', $detail->jumlah);
            }

            $pesanan->update(['status' => 'pending']);
            DB::commit();

            return back()->with('success', 'Pesanan berhasil dipulihkan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk menghapus pesanan secara permanen
    public function forceDelete($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'cancelled') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang bisa dihapus permanen!');
        }

        try {
            $pesanan->delete(); // Ini akan otomatis menghapus detail pesanan karena ada onDelete('cascade')
            return back()->with('success', 'Pesanan berhasil dihapus secara permanen!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Halaman riwayat pesanan untuk USER
    public function pesanan(Request $request)
    {
        // Query ini sudah benar dari perbaikan kita sebelumnya
        $query = Auth::user()->pesanans()->with('detailPesanans.produk')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->paginate(10);

        return view('user.pesanan', compact('pesanans'));
    }
}
