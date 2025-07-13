<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use App\Models\Artikel;

class KategoriController extends Controller
{
    public function indexUser()
    {
        // Ambil 4 kategori produk untuk ditampilkan
        $kategoris = Kategori::withCount('produks')->latest()->take(4)->get();

        // Ambil 3 artikel terbaru yang sudah di-publish
        $artikels = Artikel::where('status', 'published')
                           ->latest('tanggal_post')
                           ->take(3)
                           ->get();

        // Kirim kedua data ke view
        return view('home', compact('kategoris', 'artikels'));
    }

    // ✅ Menampilkan form tambah & daftar kategori
    public function index()
    {
        $kategoris = Kategori::withCount('produks')->get();
        $kategoriToEdit = null; // Penting untuk form dinamis
        return view('admin.kategoris.index', compact('kategoris', 'kategoriToEdit'));
    }

    // ✅ Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            'deskripsi' => 'nullable|string',
            'gambar_kategori' => 'nullable|image|max:2048'
        ], [
            'nama_kategori.unique' => 'Gagal Menyimpan data, Kategori sudah tersedia, silahkan gunakan nama lain.'
        ]);

        $data = $request->only(['nama_kategori', 'deskripsi']);

        if ($request->hasFile('gambar_kategori')) {
            $data['gambar_kategori'] = $request->file('gambar_kategori')->store('kategori', 'public');
        }

        Kategori::create($data);

        return redirect()->route('admin.kategoris.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // ✅ Menampilkan form edit dan daftar kategori
    public function edit($id)
    {
        $kategoris = Kategori::withCount('produks')->get();
        $kategoriToEdit = Kategori::findOrFail($id);

        // Kirim data kategori yg sedang diedit
        return view('admin.kategoris.index', compact('kategoris', 'kategoriToEdit'));
    }

    // ✅ Memperbarui data kategori
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
            'gambar_kategori' => 'nullable|image|max:2048'
        ], [
            'nama_kategori.unique' => 'Gagal Menyimpan data, Kategori sudah tersedia, silahkan gunakan nama lain.'
        ]);

        $data = $request->only(['nama_kategori', 'deskripsi']);

        if ($request->hasFile('gambar_kategori')) {
            if ($kategori->gambar_kategori && Storage::disk('public')->exists($kategori->gambar_kategori)) {
                Storage::disk('public')->delete($kategori->gambar_kategori);
            }

            $data['gambar_kategori'] = $request->file('gambar_kategori')->store('kategori', 'public');
        }

        $kategori->update($data);

        return redirect()->route('admin.kategoris.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // ✅ Menghapus kategori
    public function destroy(Kategori $kategori)
    {
        if ($kategori->gambar_kategori && Storage::disk('public')->exists($kategori->gambar_kategori)) {
            Storage::disk('public')->delete($kategori->gambar_kategori);
        }

        $kategori->delete();

        return redirect()->route('admin.kategoris.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
