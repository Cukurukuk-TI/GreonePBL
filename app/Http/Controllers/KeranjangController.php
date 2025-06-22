<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $keranjangs = Keranjang::with('produk.kategori')
            ->where('user_id', Auth::id())
            ->get();

        $totalHarga = $keranjangs->sum('subtotal');

        return view('keranjang.index', compact('keranjangs', 'totalHarga'));
    }

    // Menambah produk ke keranjang
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok_produk <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok produk telah habis.');
        }

        if ($request->jumlah > $produk->stok_produk) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        try {
            DB::beginTransaction();

            $keranjangExisting = Keranjang::where('user_id', Auth::id())
                ->where('produk_id', $request->produk_id)
                ->first();

            if ($keranjangExisting) {
                $jumlahBaru = $keranjangExisting->jumlah + $request->jumlah;

                if ($jumlahBaru > $produk->stok_produk) {
                    return redirect()->back()->with('error', 'Total jumlah di keranjang melebihi stok!');
                }

                $keranjangExisting->update([
                    'jumlah' => $jumlahBaru,
                    'subtotal' => $jumlahBaru * $produk->harga_produk
                ]);
            } else {
                Keranjang::create([
                    'user_id' => Auth::id(),
                    'produk_id' => $request->produk_id,
                    'jumlah' => $request->jumlah,
                    'harga_satuan' => $produk->harga_produk,
                    'subtotal' => $request->jumlah * $produk->harga_produk
                ]);
            }

            DB::commit();
            return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Update jumlah produk di keranjang (Dibuat untuk merespon dengan JSON untuk AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $keranjang = Keranjang::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $produk = $keranjang->produk;
        if ($request->jumlah > $produk->stok_produk) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia! Stok tersisa: ' . $produk->stok_produk,
            ], 422);
        }

        $keranjang->update([
            'jumlah' => $request->jumlah,
            'subtotal' => $request->jumlah * $keranjang->harga_satuan
        ]);

        // Hitung ulang total keranjang
        $totalKeranjang = Keranjang::where('user_id', Auth::id())->get()->sum('subtotal');

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk berhasil diupdate!',
            'subtotal_item' => 'Rp ' . number_format($keranjang->subtotal, 0, ',', '.'),
            'total_keranjang' => 'Rp ' . number_format($totalKeranjang, 0, ',', '.')
        ]);
    }

    // Hapus produk dari keranjang
    public function destroy($id)
    {
        $keranjang = Keranjang::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $keranjang->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    // Kosongkan keranjang
    public function clear()
    {
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    // Halaman checkout dari keranjang
    public function checkout()
    {
        $user = Auth::user();
        $keranjangs = Keranjang::with('produk')
            ->where('user_id', $user->id)
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Ambil data yang diperlukan untuk view checkout
        $alamats = Alamat::where('user_id', $user->id)->get();
        $promos = Promo::where('is_active', true)->get();
        $totalHarga = $keranjangs->sum('subtotal');

        return view('keranjang.checkout', compact('keranjangs', 'totalHarga', 'alamats', 'promos'));
    }

    // Proses checkout dari keranjang
    public function processCheckout(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamats,id,user_id,' . Auth::id(),
            'metode_pengiriman' => 'required|string|in:diantar,jemput',
            'metode_pembayaran' => 'required|string|in:cod,transfer',
            'promo_id' => 'nullable|exists:promos,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $keranjangs = Keranjang::with('produk')->where('user_id', $user->id)->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong!');
        }

        try {
            DB::beginTransaction();

            // 1. Cek stok semua produk di keranjang
            foreach ($keranjangs as $item) {
                $produk = $item->produk;
                if ($item->jumlah > $produk->stok_produk) {
                    throw new \Exception('Stok produk ' . $produk->nama_produk . ' tidak mencukupi.');
                }
            }

            // 2. Siapkan data untuk pesanan
            $alamat = Alamat::findOrFail($request->alamat_id);
            $alamat_pengiriman = "{$alamat->label}: {$alamat->nama_penerima}, {$alamat->nomor_hp}, {$alamat->detail_alamat}, {$alamat->kota}, {$alamat->provinsi}, {$alamat->kode_pos}";

            $subtotal = $keranjangs->sum('subtotal');
            $ongkos_kirim = ($request->metode_pengiriman == 'diantar') ? 10000 : 0;

            // 3. Hitung diskon dari promo
            $diskon = 0;
            $promo = null;
            if ($request->promo_id) {
                $promo = Promo::find($request->promo_id);
                if ($promo && $promo->is_active && $subtotal >= $promo->minimum_belanja) {
                    $diskon = ($subtotal * $promo->besaran_potongan) / 100;
                }
            }

            $total_harga = $subtotal - $diskon + $ongkos_kirim;

            // 4. Buat record Pesanan
            // ===== PERBAIKAN DI BLOK INI =====
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'kode_pesanan' => 'INV-' . time() . $user->id,
                'alamat_pengiriman' => $alamat_pengiriman,
                // 'subtotal' => $subtotal, // <-- DIHAPUS
                'diskon' => $diskon,
                'ongkos_kirim' => $ongkos_kirim,
                'total_harga' => $total_harga,
                'status' => 'pending',
                'metode_pembayaran' => $request->metode_pembayaran,
                'metode_pengiriman' => $request->metode_pengiriman,
                'catatan' => $request->catatan,
                'promo_id' => $promo ? $promo->id : null,
            ]);

            // 5. Buat record DetailPesanan dan kurangi stok
            foreach ($keranjangs as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->harga_satuan,
                    'subtotal' => $item->subtotal,
                ]);

                $item->produk->decrement('stok_produk', $item->jumlah);
            }

            // 6. Kosongkan keranjang
            Keranjang::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('pesanans.success', $pesanan->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('keranjang.checkout')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
