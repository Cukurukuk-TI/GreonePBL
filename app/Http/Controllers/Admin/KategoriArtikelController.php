<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriArtikels = KategoriArtikel::latest()->paginate(10);

        return view('admin.kategori-artikel.index', compact('kategoriArtikels'));
    }

    public function create()
    {
        return view('admin.kategori-artikel.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_artikels,nama',
        ]);

        KategoriArtikel::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
        ]);

        return redirect()->route('admin.artikel.kategori.index')
                         ->with('success', 'Kategori artikel berhasil ditambahkan.');
    }
    public function show(KategoriArtikel $kategoriArtikel) {

    }
    public function edit(KategoriArtikel $kategoriArtikel) {

    }
    public function update(Request $request, KategoriArtikel $kategoriArtikel) {

    }
    public function destroy(KategoriArtikel $kategoriArtikel) {

    }
}
