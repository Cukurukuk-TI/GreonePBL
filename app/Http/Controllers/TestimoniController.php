<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use App\Models\Pesanan; // Import model Pesanan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Untuk Admin: Menampilkan semua testimoni
        $testimonis = Testimoni::with(['user', 'produk'])->latest()->get();
        return view('testimoni.index', compact('testimonis'));
    }

    /**
     * Show the form for creating a new resource (as a popup).
     */
    public function create($pesanan_id)
    {
        // 1. Ambil pesanan beserta relasi detail dan produknya
        $pesanan = Pesanan::with('details.produk')->findOrFail($pesanan_id);

        // 2. Otorisasi: Pastikan pesanan ini milik user yang sedang login
        if ($pesanan->user_id !== Auth::id()) {
            return response('Akses ditolak.', 403);
        }
        
        // 3. Validasi: Hanya pesanan 'complete' yang bisa diulas
        if ($pesanan->status !== 'complete') {
            return response('Testimoni hanya bisa diberikan untuk pesanan yang sudah selesai.', 403);
        }
        
        // 4. Ambil detail produk pertama dari pesanan
        $detail = $pesanan->details->first();
        if (!$detail) {
            return response('Pesanan ini tidak memiliki produk untuk diulas.', 404);
        }

        // 5. Cek apakah testimoni untuk produk ini sudah ada
        $existingTestimoni = Testimoni::where('user_id', Auth::id())
                                    ->where('produk_id', $detail->produk_id)
                                    ->exists();
        
        if ($existingTestimoni) {
            return response('<div class="p-8 text-center bg-white rounded-lg"><p class="text-gray-700">Anda sudah memberikan ulasan untuk produk ini.</p><button onclick="closeTestimoniModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Tutup</button></div>', 409);
        }
        
        // 6. Kirim data ke view modal
        return view('testimoni.create', compact('pesanan', 'detail'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'produk_id' => 'required|exists:produks,id', // Validasi produk_id dari form
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:500',
            'foto_testimoni' => 'nullable|image|max:2048',
        ]);

        $pesanan = Pesanan::findOrFail($request->pesanan_id);

        if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'complete') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memberikan testimoni pada pesanan ini.');
        }

        // Cek apakah produk yang diulas benar-benar ada dalam pesanan tersebut
        $productInOrder = $pesanan->details()->where('produk_id', $request->produk_id)->exists();
        if (!$productInOrder) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan dalam pesanan yang Anda ulas.');
        }

        $existingTestimoni = Testimoni::where('user_id', Auth::id())
                                    ->where('produk_id', $request->produk_id)
                                    ->exists();
        if ($existingTestimoni) {
            return redirect()->back()->with('error', 'Anda sudah memberikan testimoni untuk produk ini.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_testimoni')) {
            $fotoPath = $request->file('foto_testimoni')->store('testimoni_photos', 'public');
        }

        Testimoni::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'foto_testimoni' => $fotoPath,
        ]);

        return redirect()->route('user.pesanan')->with('success', 'Testimoni berhasil ditambahkan!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimoni $testimoni)
    {
        // Pastikan hanya admin yang bisa menghapus atau user yang memiliki testimoni itu sendiri
        // Untuk tujuan ini, kita asumsikan hanya admin yang bisa menghapus dari halaman admin.
        // Jika Anda ingin user bisa menghapus testimoninya sendiri, tambahkan pengecekan Auth::id() == $testimoni->user_id
        
        if ($testimoni->foto_testimoni && Storage::disk('public')->exists($testimoni->foto_testimoni)) {
            Storage::disk('public')->delete($testimoni->foto_testimoni);
        }
        $testimoni->delete();
        return redirect()->route('testimoni.index')->with('success', 'Testimoni berhasil dihapus.');
    }
}