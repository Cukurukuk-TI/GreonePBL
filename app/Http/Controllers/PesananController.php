<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    // Halaman buat pesanan (untuk user)
    public function create(Request $request, $produkId)
    {
        $produk = Produk::findOrFail($produkId);
        $promos = Promo::getActivePromos();
        
        // Ambil alamat user yang sedang login
        $alamats = Alamat::where('user_id', Auth::id())->get();
        
        // Ambil jumlah dari parameter URL jika ada
        $defaultJumlah = $request->get('jumlah', 1);
        
        // Validasi jumlah tidak melebihi stok
        if ($defaultJumlah > $produk->stok_produk) {
            $defaultJumlah = $produk->stok_produk;
        }
        
        return view('pesanans.create', compact('produk', 'promos', 'alamats', 'defaultJumlah'));
    }

    // Proses simpan pesanan
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'alamat_id' => 'nullable|exists:alamats,id',
            'alamat_pengiriman_custom' => 'nullable|string|max:500',
            'promo_id' => 'nullable|exists:promos,id'
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($request->produk_id);
            
            // Cek stok
            if ($produk->stok_produk < $request->jumlah) {
                return back()->with('error', 'Stok produk tidak mencukupi');
            }

            // Tentukan alamat pengiriman
            $alamat_pengiriman = '';
            if ($request->alamat_id) {
                $alamat = Alamat::find($request->alamat_id);
                $alamat_pengiriman = $alamat->detail_alamat . ', ' . $alamat->kota . ', ' . $alamat->provinsi;
            } else {
                $alamat_pengiriman = $request->alamat_pengiriman_custom ?: 'Alamat belum ditentukan';
            }

            // Hitung subtotal
            $subtotal = $produk->harga_produk * $request->jumlah;
            
            // Hitung diskon
            $diskon = 0;
            $promo = null;
            if ($request->promo_id) {
                $promo = Promo::find($request->promo_id);
                if ($promo && $promo->isValid()) {
                    $diskon = $promo->calculateDiscount($subtotal);
                }
            }

            // Fixed values
            $ongkos_kirim = 10000;
            $pajak = 0;
            
            // Total harga
            $total_harga = $subtotal - $diskon + $ongkos_kirim + $pajak;

            // Buat pesanan
            $pesanan = Pesanan::create([
                'kode_pesanan' => Pesanan::generateKodePesanan(),
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'promo_id' => $request->promo_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $produk->harga_produk,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'ongkos_kirim' => $ongkos_kirim,
                'pajak' => $pajak,
                'total_harga' => $total_harga,
                'alamat_pengiriman' => $alamat_pengiriman,
                'metode_pembayaran' => 'BNI Virtual Account',
                'metode_pengiriman' => 'SiCepat Ultimate',
                'status' => 'pending'
            ]);

            // Update stok produk
            $produk->decrement('stok_produk', $request->jumlah);

            DB::commit();

            return redirect()->route('pesanans.success', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Halaman sukses pesanan
    public function success($id)
    {
        $pesanan = Pesanan::with(['produk', 'promo'])->findOrFail($id);
        return view('pesanans.success', compact('pesanan'));
    }

    // Index untuk admin (daftar pesanan aktif saja - TIDAK termasuk cancelled)
    public function index()
    {
        $pesanans = Pesanan::with(['user', 'produk'])
            ->whereNotIn('status', ['cancelled']) // Exclude cancelled orders
            ->orderBy('created_at', 'desc')
            ->get();
            
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
}