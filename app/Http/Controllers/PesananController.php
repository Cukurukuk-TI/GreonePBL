<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Alamat; // Tambahkan import ini
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
            'alamat_id' => 'nullable|exists:alamats,id', // Validasi alamat_id
            'alamat_pengiriman_custom' => 'nullable|string|max:500', // Alamat custom jika tidak pilih dari list
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
                // Jika pilih dari alamat yang sudah ada
                $alamat = Alamat::find($request->alamat_id);
                $alamat_pengiriman = $alamat->detail_alamat . ', ' . $alamat->kota . ', ' . $alamat->provinsi;
            } else {
                // Jika input alamat custom
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

    // Index untuk admin (daftar semua pesanan)
    public function index()
    {
        $pesanans = Pesanan::with(['user', 'produk'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pesanans.index', compact('pesanans'));
    }

    // Update status pesanan (untuk admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,complete'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}