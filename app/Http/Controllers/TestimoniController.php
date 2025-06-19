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
        $pesanan = Pesanan::with('produk')->findOrFail($pesanan_id);

        if ($pesanan->status !== 'complete') {
            return redirect()->back()->with('error', 'Testimoni hanya bisa diberikan untuk pesanan yang sudah selesai.');
        }

        // Cek apakah user sudah memberikan testimoni untuk pesanan ini
        $existingTestimoni = Testimoni::where('user_id', Auth::id())
                                    ->where('produk_id', $pesanan->produk_id)
                                    ->first();

        if ($existingTestimoni) {
            return redirect()->back()->with('error', 'Anda sudah memberikan testimoni untuk produk ini.');
        }

        return view('testimoni.create', compact('pesanan'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id', // Validasi pesanan_id
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:500',
            'foto_testimoni' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $pesanan = Pesanan::findOrFail($request->pesanan_id);

        // Pastikan pesanan adalah milik user yang sedang login dan statusnya 'complete'
        if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'complete') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memberikan testimoni pada pesanan ini.');
        }

        // Pastikan user belum memberikan testimoni untuk produk ini dari pesanan ini
        $existingTestimoni = Testimoni::where('user_id', Auth::id())
                                    ->where('produk_id', $pesanan->produk_id)
                                    ->first();
        if ($existingTestimoni) {
            return redirect()->back()->with('error', 'Anda sudah memberikan testimoni untuk produk ini.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_testimoni')) {
            $fotoPath = $request->file('foto_testimoni')->store('testimoni_photos', 'public');
        }

        Testimoni::create([
            'user_id' => Auth::id(),
            'produk_id' => $pesanan->produk_id,
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