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

class KeranjangController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja pengguna.
     */
    public function index()
    {
        $keranjangs = Keranjang::with('produk.kategori')
            ->where('user_id', Auth::id())
            ->get();

        $totalHarga = $keranjangs->sum('subtotal');

        return view('keranjang.index', compact('keranjangs', 'totalHarga'));
    }

    /**
     * Menambah produk ke keranjang.
     */
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
                    // Jangan kirim JSON, tapi redirect dengan pesan error
                    return redirect()->back()->with('error', 'Total jumlah di keranjang melebihi stok!');
                }

                // Gunakan increment untuk menambah jumlah, ini lebih efisien
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
            // Pastikan baris ini ada dan tidak diubah
            return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update jumlah produk di keranjang .
     */
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

    /**
     * Menghapus satu item dari keranjang.
     */
    public function destroy($id)
    {
        Keranjang::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    /**
     * Mengosongkan seluruh isi keranjang.
     */
    public function clear()
    {
        Keranjang::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    /**
     * Menampilkan halaman checkout dengan validasi stok tahap pertama.
     */
    public function checkout()
    {
        $user = Auth::user();
        $keranjangs = Keranjang::with('produk')->where('user_id', $user->id)->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Validasi Stok Tahap 1: Sebelum menampilkan halaman checkout.
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

    /**
     * Memproses pesanan dari keranjang.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamats,id,user_id,' . Auth::id(),
            'metode_pengiriman' => 'required|string|in:diantar,jemput',
            'metode_pembayaran' => 'required|string|in:cod,transfer',
            'promo_id' => 'nullable|exists:promos,id',
        ]);

        $keranjangs = Keranjang::with('produk')->where('user_id', Auth::id())->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong!');
        }

        try {
            // Memulai transaksi database untuk memastikan semua operasi berhasil atau tidak sama sekali.
            return DB::transaction(function () use ($request, $keranjangs) {
                // Validasi Stok Tahap 2: Di dalam transaksi untuk mencegah race condition.
                $this->_validateStockInCart($keranjangs, true);

                // Menyiapkan data pesanan (kalkulasi total, diskon, ongkir).
                $orderData = $this->_prepareOrderData($request, $keranjangs);

                // Membuat pesanan utama.
                $pesanan = Pesanan::create($orderData['pesanan']);

                // Membuat detail pesanan dan mengurangi stok untuk setiap item.
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

                // Mengosongkan keranjang setelah pesanan berhasil dibuat.
                Keranjang::where('user_id', Auth::id())->delete();

                return redirect()->route('pesanans.success', $pesanan->id)->with('success', 'Pesanan berhasil dibuat!');
            });
        } catch (Exception $e) {
            return redirect()->route('keranjang.checkout')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Memvalidasi stok untuk semua item di keranjang.
     *
     * @param  \Illuminate\Support\Collection $keranjangs
     * @param  bool $lockForUpdate Mengunci baris data untuk mencegah race condition.
     * @throws \Exception
     */
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

    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Support\Collection $keranjangs
     * @return array
     */
    private function _prepareOrderData(Request $request, Collection $keranjangs): array
    {
        $alamat = Alamat::findOrFail($request->alamat_id);
        $alamat_pengiriman = "{$alamat->label}: {$alamat->nama_penerima}, {$alamat->nomor_hp}, {$alamat->detail_alamat}, {$alamat->kota}, {$alamat->provinsi}, {$alamat->kode_pos}";
        $subtotal = $keranjangs->sum('subtotal');
        $ongkos_kirim = ($request->metode_pengiriman == 'diantar') ? 10000 : 0;
        $diskon = 0;
        $promoId = null;

        if ($request->promo_id) {
            $promo = Promo::find($request->promo_id);
            if ($promo && $promo->is_active && $subtotal >= $promo->minimum_belanja) {
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
