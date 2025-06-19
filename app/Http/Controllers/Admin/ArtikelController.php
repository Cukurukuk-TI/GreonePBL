<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Data untuk Card Statistik
        $totalArtikel = Artikel::count();
        $artikelDihapus = Artikel::onlyTrashed()->count();

        // Data untuk Tabel
        $artikels = Artikel::with('kategoriArtikel')->latest()->paginate(5, ['*'], 'artikel_page');
        $kategoriArtikels = KategoriArtikel::latest()->paginate(5, ['*'], 'kategori_page');

        return view('admin.artikel.index', compact(
            'totalArtikel',
            'artikelDihapus',
            'artikels',
            'kategoriArtikels'
        ));
    }

    public function create()
    {
        // Ambil semua data kategori untuk ditampilkan di dropdown
        $kategoriArtikels = KategoriArtikel::orderBy('nama')->get();
        return view('admin.artikel.create', compact('kategoriArtikels'));
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'tanggal_post' => 'required|date',
            'kategori_artikel_id' => 'required|exists:kategori_artikels,id',
            'konten' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikel_images', 'public');
            $validatedData['gambar'] = $gambarPath;
        }

        $validatedData['slug'] = Str::slug($request->judul) . '-' . time();
        Artikel::create($validatedData);

        return redirect()->route('admin.artikel.index')
                         ->with('success', 'Artikel baru berhasil dipublikasikan!');
    }

    public function show(Artikel $artikel) {

    }
    /**
     * Menampilkan form untuk mengedit artikel.
     */
    public function edit(Artikel $artikel)
    {
        $kategoriArtikels = KategoriArtikel::orderBy('nama')->get();
        // Kirim data artikel yang spesifik dan daftar kategori ke view
        return view('admin.artikel.edit', compact('artikel', 'kategoriArtikels'));
    }

    /**
     * Memperbarui artikel yang ada di database.
     */
    public function update(Request $request, Artikel $artikel)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'tanggal_post' => 'required|date',
            'kategori_artikel_id' => 'required|exists:kategori_artikels,id',
            'konten' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        // Jika ada gambar baru yang di-upload, proses dan update
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $gambarPath = $request->file('gambar')->store('artikel_images', 'public');
            $validatedData['gambar'] = $gambarPath;
        }

        $validatedData['slug'] = Str::slug($request->judul) . '-' . time();
        $artikel->update($validatedData);

        return redirect()->route('admin.artikel.index')
                         ->with('success', 'Artikel berhasil diperbarui!');
    }
    public function destroy(Artikel $artikel) {

    }
}
