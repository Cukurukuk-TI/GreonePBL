<?php
namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StorePesananRequest;

class PesananController extends Controller
{
    /**
     * Menampilkan halaman form untuk membuat pesanan baru.
     *
     * @param Request $request
     * @param int $produkId
     * @return View
     */
    public function create(Request $request, $produkId): View
    {
        $produk = Produk::findOrFail($produkId);
        $promos = Promo::where('status', 'aktif')->get(); // Asumsi ada scope atau kondisi
        $alamats = Alamat::where('user_id', Auth::id())->get();
        $defaultJumlah = $request->input('jumlah', 1);

        if ($defaultJumlah > $produk->stok_produk) {
            $defaultJumlah = $produk->stok_produk;
        }
        
        return view('user.pesanans.create', compact('produk', 'promos', 'alamats', 'defaultJumlah'));
    }

    /**
     * Memproses dan menyimpan pesanan baru dari user.
     *
     * @param StorePesananRequest $request
     * @return RedirectResponse
     */
    public function store(StorePesananRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($validated['produk_id']);
            
            if ($produk->stok_produk < $validated['jumlah']) {
                return back()->with('error', 'Stok produk tidak mencukupi.')->withInput();
            }

            $alamat_pengiriman = $this->getAlamatPengiriman($validated);
            $subtotal = $produk->harga_produk * $validated['jumlah'];
            $diskon = $this->calculateDiscount($validated, $subtotal);
            
            $ongkos_kirim = 10000;
            $pajak = 0;
            $total_harga = $subtotal - $diskon + $ongkos_kirim + $pajak;

            $pesanan = Pesanan::create([
                'kode_pesanan' => 'INV-' . time(), // Sebaiknya gunakan method yang lebih robust
                'user_id' => Auth::id(),
                'produk_id' => $validated['produk_id'],
                'promo_id' => $validated['promo_id'] ?? null,
                'jumlah' => $validated['jumlah'],
                'harga_satuan' => $produk->harga_produk,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'ongkos_kirim' => $ongkos_kirim,
                'pajak' => $pajak,
                'total_harga' => $total_harga,
                'alamat_pengiriman' => $alamat_pengiriman,
                'status' => 'pending'
            ]);

            $produk->decrement('stok_produk', $validated['jumlah']);

            DB::commit();

            return redirect()->route('pesanans.success', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan halaman konfirmasi setelah pesanan berhasil dibuat.
     *
     * @param Pesanan $pesanan
     * @return View
     */
    public function success(Pesanan $pesanan): View
    {
        // Pastikan user hanya bisa melihat pesanannya sendiri
        abort_if($pesanan->user_id !== Auth::id(), 403);
        
        $pesanan->load(['produk', 'promo', 'user']);
        return view('user.pesanans.success', compact('pesanan'));
    }

    // --- Helper Methods ---
    private function getAlamatPengiriman(array $validatedData): string
    {
        if (!empty($validatedData['alamat_id'])) {
            $alamat = Alamat::find($validatedData['alamat_id']);
            return $alamat->detail_alamat . ', ' . $alamat->kota . ', ' . $alamat->provinsi;
        }
        return $validatedData['alamat_pengiriman_custom'] ?? 'Alamat tidak ditentukan.';
    }

    private function calculateDiscount(array $validatedData, float $subtotal): float
    {
        if (empty($validatedData['promo_id'])) return 0;

        $promo = Promo::find($validatedData['promo_id']);
        // Sebaiknya ada method di model Promo untuk validasi dan kalkulasi
        if ($promo && $promo->status == 'aktif') { 
            // Logika diskon sederhana, perlu disesuaikan
            return ($subtotal * $promo->persentase_diskon / 100);
        }
        return 0;
    }
}