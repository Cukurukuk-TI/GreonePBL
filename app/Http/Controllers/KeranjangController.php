<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\DetailPesanan;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;

class KeranjangController extends Controller
{
    public function jumlahCart()
    {
        $cart = session()->get('cart', []);
        return count($cart);
    }

    public function index()
    {
        $keranjangs = Keranjang::with('produk.kategori')
            ->where('user_id', Auth::id())
            ->get();

        $totalHarga = $keranjangs->sum('subtotal');

        return view('keranjang.index', compact('keranjangs', 'totalHarga'));
    }

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

                $keranjangExisting->increment('jumlah', $request->jumlah);

            } else {
                Keranjang::create([
                    'user_id' => Auth::id(),
                    'produk_id' => $request->produk_id,
                    'jumlah' => $request->jumlah,
                    'harga_satuan' => $produk->harga_produk,
                ]);
            }

            DB::commit();
            return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

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
            return redirect()->route('keranjang.index')
                ->with('error', 'Jumlah melebihi stok yang tersedia! Stok tersisa: ' . $produk->stok_produk);
        }

        $keranjang->update([
            'jumlah' => $request->jumlah
        ]);

        return redirect()->route('keranjang.index')
            ->with('success', 'Jumlah produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        Keranjang::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        Keranjang::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function checkout()
    {
        $user = Auth::user();
        $keranjangs = Keranjang::with('produk')->where('user_id', $user->id)->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong!');
        }

        try {
            $this->_validateStockInCart($keranjangs);
        } catch (Exception $e) {
            return redirect()->route('keranjang.index')->with('error', $e->getMessage());
        }

        $alamats = Alamat::where('user_id', $user->id)->get();
        $promos = Promo::where('is_active', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->get();
        $totalHarga = $keranjangs->sum('subtotal');

        return view('keranjang.checkout', compact('keranjangs', 'totalHarga', 'alamats', 'promos'));
    }

    public function processCheckout(Request $request)
    {
        // Validasi input awal
        $request->validate([
            'alamat_id' => 'required|exists:alamats,id,user_id,' . Auth::id(),
            'metode_pengiriman' => 'required|string|in:diantar,jemput',
            'metode_pembayaran' => 'required|string|in:cod,transfer',
            'promo_id' => 'nullable|exists:promos,id',
        ]);

        try {
            $keranjangs = Keranjang::with('produk')->where('user_id', Auth::id())->get();

            // Cek jika keranjang kosong setelah validasi awal
            if ($keranjangs->isEmpty()) {
                throw new Exception('Keranjang Anda kosong!');
            }
            // =============================================================

            // Validasi stok
            $this->_validateStockInCart($keranjangs, true);
            // Siapkan data pesanan
            $orderData = $this->_prepareOrderData($request, $keranjangs);

            // === PERUBAHAN UTAMA DIMULAI DI SINI ===

            if ($request->metode_pembayaran == 'transfer') {
                // Untuk pembayaran transfer, buat pesanan dengan status 'unpaid'
                $orderData['pesanan']['status'] = 'unpaid';

                $pesanan = DB::transaction(function () use ($orderData, $keranjangs) {
                    $pesanan = Pesanan::create($orderData['pesanan']);
                    foreach ($keranjangs as $item) {
                        DetailPesanan::create([
                            'pesanan_id' => $pesanan->id,
                            'produk_id' => $item->produk_id,
                            'jumlah' => $item->jumlah,
                            'harga_satuan' => $item->harga_satuan,
                            'subtotal' => $item->subtotal,
                        ]);
                        // Stok belum dikurangi di sini
                    }
                    return $pesanan;
                });

                // Hapus keranjang setelah pesanan dibuat
                Keranjang::where('user_id', Auth::id())->delete();

                // Arahkan ke halaman konfirmasi baru, BUKAN ke Midtrans/halaman sukses
                if ($request->expectsJson()) {
                    return response()->json([
                        'redirect_url' => route('pesanan.konfirmasi', $pesanan->id)
                    ]);
                }
                return redirect()->route('pesanan.konfirmasi', $pesanan->id);

            } else { // Untuk metode pembayaran COD
                // Untuk COD, status langsung 'pending'
                $orderData['pesanan']['status'] = 'pending';

                $pesanan = DB::transaction(function() use ($orderData, $keranjangs) {
                    $pesanan = Pesanan::create($orderData['pesanan']);
                    foreach ($keranjangs as $item) {
                        DetailPesanan::create([
                            'pesanan_id' => $pesanan->id,
                            'produk_id' => $item->produk_id,
                            'jumlah' => $item->jumlah,
                            'harga_satuan' => $item->harga_satuan,
                            'subtotal' => $item->subtotal,
                        ]);
                        // Langsung kurangi stok untuk COD
                        if ($item->produk) {
                            $item->produk->decrement('stok_produk', $item->jumlah);
                        }
                    }
                    return $pesanan;
                });

                Keranjang::where('user_id', Auth::id())->delete();

                if ($request->expectsJson()) {
                    return response()->json([
                        'redirect_url' => route('pesanans.success', $pesanan->id)
                    ]);
                }
                return redirect()->route('pesanans.success', $pesanan->id);
            }

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->route('keranjang.checkout')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function _validateStockInCart(Collection $keranjangs, bool $lockForUpdate = false): void
    {
        foreach ($keranjangs as $item) {
            $produk = $lockForUpdate
                ? Produk::lockForUpdate()->find($item->produk_id)
                : $item->produk;

            if (!$produk || $item->jumlah > $produk->stok_produk) {
                throw new Exception('Stok produk "' . ($produk->nama_produk ?? 'N/A') . '" tidak mencukupi. Sisa stok: ' . ($produk->stok_produk ?? 0));
            }
        }
    }

    private function _prepareOrderData(Request $request, Collection $keranjangs): array
    {
        $alamat = Alamat::findOrFail($request->alamat_id);
        $alamat_pengiriman = "{$alamat->label}: {$alamat->nama_penerima}, {$alamat->nomor_hp}, {$alamat->detail_alamat}, {$alamat->kota}, {$alamat->provinsi}";
        $subtotal = $keranjangs->sum('subtotal');
        $ongkos_kirim = ($request->metode_pengiriman == 'diantar') ? 10000 : 0;
        $diskon = 0;
        $promoId = null;

        if ($request->promo_id) {
            $promo = Promo::find($request->promo_id);
            if ($promo && $promo->isValid() && $subtotal >= $promo->minimum_belanja) {
                $diskon = ($subtotal * $promo->besaran_potongan) / 100;
                $promoId = $promo->id;
            }
        }

        $total_harga = $subtotal - $diskon + $ongkos_kirim;

        return [
            'pesanan' => [
                'user_id' => Auth::id(),
                'kode_pesanan' => 'INV-' . time() . Auth::id(),
                'alamat_pengiriman' => $alamat_pengiriman,
                'diskon' => $diskon,
                'ongkos_kirim' => $ongkos_kirim,
                'total_harga' => $total_harga,
                'status' => 'pending',
                'metode_pembayaran' => $request->metode_pembayaran,
                'metode_pengiriman' => $request->metode_pengiriman,
                'catatan' => $request->catatan,
                'promo_id' => $promoId,
            ]
        ];
    }
}
