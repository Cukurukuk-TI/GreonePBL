<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

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
        
        // Cek stok
        if ($request->jumlah > $produk->stok_produk) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        try {
            DB::beginTransaction();

            // Cek apakah produk sudah ada di keranjang
            $keranjangExisting = Keranjang::where('user_id', Auth::id())
                ->where('produk_id', $request->produk_id)
                ->first();

            if ($keranjangExisting) {
                // Update jumlah jika produk sudah ada
                $jumlahBaru = $keranjangExisting->jumlah + $request->jumlah;
                
                // Cek stok lagi setelah penambahan
                if ($jumlahBaru > $produk->stok_produk) {
                    return redirect()->back()->with('error', 'Total jumlah melebihi stok yang tersedia!');
                }

                $keranjangExisting->update([
                    'jumlah' => $jumlahBaru,
                    'subtotal' => $jumlahBaru * $produk->harga_produk
                ]);
            } else {
                // Buat entri baru jika produk belum ada
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
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan ke keranjang!');
        }
    }

    // Update jumlah produk di keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $keranjang = Keranjang::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cek stok
        if ($request->jumlah > $keranjang->produk->stok_produk) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        $keranjang->update([
            'jumlah' => $request->jumlah,
            'subtotal' => $request->jumlah * $keranjang->harga_satuan
        ]);

        return redirect()->back()->with('success', 'Jumlah produk berhasil diupdate!');
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
        $keranjangs = Keranjang::with('produk.kategori')
            ->where('user_id', Auth::id())
            ->get();

        if ($keranjangs->count() == 0) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong!');
        }

        $totalHarga = $keranjangs->sum('subtotal');

        return view('keranjang.checkout', compact('keranjangs', 'totalHarga'));
    }

    // Proses checkout dari keranjang
    public function processCheckout(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'alamat_pengiriman' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        $keranjangs = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        if ($keranjangs->count() == 0) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong!');
        }

        try {
            DB::beginTransaction();

            // Cek stok semua produk
            foreach ($keranjangs as $item) {
                if ($item->jumlah > $item->produk->stok_produk) {
                    return redirect()->back()->with('error', 'Stok produk ' . $item->produk->nama_produk . ' tidak mencukupi!');
                }
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'nama_pemesan' => $request->nama_pemesan,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'nomor_telepon' => $request->nomor_telepon,
                'catatan' => $request->catatan,
                'total_harga' => $keranjangs->sum('subtotal'),
                'status_pesanan' => 'pending',
                'tanggal_pesanan' => now(),
            ]);

            // Buat detail pesanan dan kurangi stok
            foreach ($keranjangs as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->harga_satuan,
                    'subtotal' => $item->subtotal,
                ]);

                // Kurangi stok produk
                $item->produk->decrement('stok_produk', $item->jumlah);
            }

            // Kosongkan keranjang
            Keranjang::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('pesanans.show', $pesanan->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan!');
        }
    }

    // Get total items in cart (for navbar)
    public function getCartCount()
    {
        return Keranjang::where('user_id', Auth::id())->sum('jumlah');
    }
}