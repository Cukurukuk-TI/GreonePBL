<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Testimoni; // Import model Testimoni

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();

        // Ambil produk yang ingin diedit jika ada parameter edit
        $editProduk = null;
        if ($request->has('edit')) {
            $editProduk = Produk::find($request->edit);
        }

        return view('admin.produks.index', compact('produks', 'kategoris', 'editProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|max:100',
            'deskripsi_produk' => 'required',
            'stok_produk' => 'required|integer',
            'harga_produk' => 'required|numeric',
            'gambar_produk' => 'nullable|image|max:2048',
            'id_kategori' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only([
            'nama_produk',
            'deskripsi_produk',
            'stok_produk',
            'harga_produk',
            'id_kategori'
        ]);

        if ($request->hasFile('gambar_produk')) {
            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produks.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|max:100',
            'deskripsi_produk' => 'required',
            'stok_produk' => 'required|integer',
            'harga_produk' => 'required|numeric',
            'gambar_produk' => 'nullable|image|max:2048',
            'id_kategori' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only([
            'nama_produk',
            'deskripsi_produk',
            'stok_produk',
            'harga_produk',
            'id_kategori'
        ]);

        // Jika ada gambar baru di-upload
        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }

            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produks.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        // Hapus gambar jika ada
        if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();

        return redirect()->route('admin.produks.index')->with('success', 'Produk berhasil dihapus.');
    }

    //untuk menampilkan halaman produk yang nantinya akan diakses oleh user dalam bentuk chart
    public function showToUser(Request $request, $kategori_id = null)
    {
        // Ambil semua kategori untuk ditampilkan sebagai tombol filter
        $kategoris = Kategori::all();
        $nama_kategori_aktif = 'Semua Produk'; // Default title

        // Query dasar untuk produk
        $query = Produk::with('kategori')->latest();

        // Jika ada ID kategori yang diberikan (baik dari URL atau filter)
        if ($kategori_id) {
            $kategoriAktif = Kategori::findOrFail($kategori_id);
            $nama_kategori_aktif = $kategoriAktif->nama_kategori;
            $query->where('id_kategori', $kategori_id);
        }

        // Pencarian berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
            $nama_kategori_aktif = 'Hasil Pencarian'; // Update title jika sedang mencari
        }

        $produks = $query->get();

        return view('user.produk', [
            'produks' => $produks,
            'kategoris' => $kategoris,
            'nama_kategori' => $nama_kategori_aktif,
            'kategori_aktif_id' => $kategori_id // Kirim ID kategori aktif ke view
        ]);
    }


    public function showByKategori($id)
    {
        $kategoris = Kategori::all();
        $kategori = Kategori::findOrFail($id);
        $produks = Produk::with('kategori')->where('id_kategori', $id)->latest()->get();

        return view('user.produk', [
            'produks' => $produks,
            'kategoris' => $kategoris, // jika masih perlu
            'nama_kategori' => $kategori->nama_kategori,
        ]);
    }

    //unutk menampilkan halaman detail produk
    public function show($id)
    {
        // Langkah 1: Ambil data produk utama berdasarkan ID-nya.
        $produk = Produk::with('kategori')
            ->withCount('approvedTestimonis')
            ->withAvg('approvedTestimonis', 'rating')
            ->findOrFail($id);

        // Langkah 2: Ambil data testimoni secara terpisah, HANYA yang sudah disetujui (approved).
        $testimonis = Testimoni::where('produk_id', $produk->id)
                               ->where('status', 'approved') // Filter krusial untuk kontrol kualitas
                               ->with('user')                // Ambil juga data user-nya
                               ->latest()                    // Tampilkan yang terbaru di atas
                               ->paginate(5);                 // Batasi 5 ulasan per halaman (baik untuk performa)

        // Langkah 3: Kirim kedua variabel ('produk' dan 'testimonis') ke view.
        return view('user.deskripsiproduk', compact('produk', 'testimonis'));
    }
}
