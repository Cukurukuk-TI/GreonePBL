<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

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
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_artikels,nama',
        ]);

        $kategori = KategoriArtikel::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
        ]);

        // Kembalikan response JSON untuk di-handle oleh JavaScript
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!',
            'data'    => $kategori
        ]);
    }

    public function show(KategoriArtikel $kategoriArtikel) {

    }
    public function edit(KategoriArtikel $kategoriArtikel) {

    }
    public function update(Request $request, KategoriArtikel $kategoriArtikel)
    {
        // Validasi, pastikan nama unik kecuali untuk dirinya sendiri
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_artikels')->ignore($kategoriArtikel->id),
            ],
        ]);

        $kategoriArtikel->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!',
            'data'    => $kategoriArtikel
        ]);
    }
    public function destroy(KategoriArtikel $kategoriArtikel) {

    }
}
