<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artikels = Artikel::with('kategoriArtikel')->latest()->paginate(10);

        return view('admin.artikel.index', compact('artikels'));
    }

    public function create()
    {
        // Ambil semua data kategori untuk ditampilkan di dropdown
        $kategoriArtikels = KategoriArtikel::all();
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

        $validatedData['slug'] = Str::slug($request->judul) . '-' . time(); // Tambah time() agar unik
        Artikel::create($validatedData);

        return redirect()->route('admin.artikel.index')
                         ->with('success', 'Artikel berhasil dipublikasikan.');
    }

    public function show(Artikel $artikel) {

    }
    public function edit(Artikel $artikel) {

    }
    public function update(Request $request, Artikel $artikel) {

    }
    public function destroy(Artikel $artikel) {

    }
}
