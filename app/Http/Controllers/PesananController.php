<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PesananController extends Controller
{

    // Halaman sukses pesanan
    public function success($id)
    {
        $pesanan = Pesanan::with(['produk', 'promo'])->findOrFail($id);
        return view('pesanans.success', compact('pesanan'));
    }

    // Index untuk admin (daftar pesanan aktif saja - TIDAK termasuk cancelled)
    public function index()
    {
        $pesanans = Pesanan::with(['user', 'details.produk'])
            ->whereNotIn('status', ['cancelled']) // Exclude cancelled orders
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pesanans.index', compact('pesanans'));
    }

    // Daftar pesanan yang dibatalkan (untuk admin)
    public function cancelled()
    {
        $pesanans = Pesanan::with(['user', 'produk'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan waktu dibatalkan
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

            $pesanan = Pesanan::findOrFail($id);
            $statusLama = $pesanan->status;
            $statusBaru = $request->status;

            // Jika status berubah dari non-cancelled ke cancelled, kembalikan stok
            if ($statusLama !== 'cancelled' && $statusBaru === 'cancelled') {
                $pesanan->produk->increment('stok_produk', $pesanan->jumlah);
            }

            // Jika status berubah dari cancelled ke non-cancelled, kurangi stok
            if ($statusLama === 'cancelled' && $statusBaru !== 'cancelled') {
                // Cek stok terlebih dahulu
                if ($pesanan->produk->stok_produk < $pesanan->jumlah) {
                    return back()->with('error', 'Stok produk tidak mencukupi untuk mengaktifkan kembali pesanan ini!');
                }
                $pesanan->produk->decrement('stok_produk', $pesanan->jumlah);
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
        $pesanan = Pesanan::findOrFail($id);

        // Cek apakah pesanan memang dalam status cancelled
        if ($pesanan->status !== 'cancelled') {
            return back()->with('error', 'Pesanan ini tidak dalam status dibatalkan!');
        }

        // Cek apakah stok produk masih mencukupi
        $produk = $pesanan->produk;
        if ($produk->stok_produk < $pesanan->jumlah) {
            return back()->with('error', 'Stok produk tidak mencukupi untuk mengembalikan pesanan ini!');
        }

        try {
            DB::beginTransaction();

            // Update status ke pending
            $pesanan->update(['status' => 'pending']);

            // Kurangi stok produk kembali
            $produk->decrement('stok_produk', $pesanan->jumlah);

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
        $pesanan = Pesanan::findOrFail($id);

        // Hanya bisa menghapus pesanan yang statusnya cancelled
        if ($pesanan->status !== 'cancelled') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang bisa dihapus permanen!');
        }

        try {
            $pesanan->delete();
            return back()->with('success', 'Pesanan berhasil dihapus secara permanen!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    //
    public function pesanan(Request $request)
    {
        $query = Auth::user()->pesanans()->with('produk')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->paginate(10);

        return view('user.pesanan', compact('pesanans'));
    }

    public function cancelByUser(Pesanan $pesanan)
    {
        // 1. Otorisasi: Pastikan pesanan ini milik pengguna yang sedang login.
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // 2. Validasi: Pastikan status pesanan adalah 'pending'.
        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Pesanan ini sudah diproses dan tidak dapat dibatalkan lagi.');
        }

        try {
            // 3. Mulai Transaksi Database
            DB::transaction(function () use ($pesanan) {
                // 4. Kembalikan stok untuk setiap item dalam pesanan
                foreach ($pesanan->details as $detail) {
                    Produk::find($detail->produk_id)->increment('stok_produk', $detail->jumlah);
                }

                // 5. Ubah status pesanan menjadi 'cancelled'
                $pesanan->update(['status' => 'cancelled']);
            });

            return redirect()->route('user.pesanan')->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan: ' . $e->getMessage());
        }
    }

}
