<?php

namespace App\Http\Controllers;

// Import class yang dibutuhkan
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
// Import Form Request yang sudah kita buat
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen produk.
     * Menampilkan daftar semua produk dan form untuk menambah produk baru.
     *
     * @return View
     */
    public function index(): View
    {
        // untuk user rencananya
        return view('user.produk');
    }

    public function adminIndex(): View
    {
        // Ambil semua produk, urutkan dari yang terbaru, dan sertakan relasi 'kategori'
        $produks = Produk::with('kategori')->latest()->get();
        // Ambil semua kategori untuk ditampilkan di dropdown form
        $kategoris = Kategori::all();

        return view('admin.produk.index', compact('produks', 'kategoris'));
    }

    /**
     * Menyimpan produk baru ke database.
     *
     * @param StoreProdukRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        // Ambil data yang sudah tervalidasi dari Form Request
        $validatedData = $request->validated();

        if ($request->hasFile('gambar_produk')) {
            // Simpan gambar baru dan dapatkan path-nya
            $validatedData['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        Produk::create($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     *
     * @param Produk $produk Model Produk dari Route Model Binding
     * @return View
     */
    public function edit(Produk $produk): View
    {
        // Ambil semua produk untuk ditampilkan di tabel
        $produks = Produk::with('kategori')->latest()->get();
        // Ambil semua kategori untuk dropdown
        $kategoris = Kategori::all();

        // Kirim data produk yang akan diedit dengan nama variabel $editProduk agar form bisa mendeteksinya
        return view('admin.produk.index', [
            'produks' => $produks,
            'kategoris' => $kategoris,
            'editProduk' => $produk, // Mengirim produk yang akan diedit
        ]);
    }


    /**
     * Memperbarui data produk di database.
     *
     * @param UpdateProdukRequest $request
     * @param Produk $produk Model Produk dari Route Model Binding
     * @return RedirectResponse
     */
    public function update(UpdateProdukRequest $request, Produk $produk): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }
            $validatedData['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        $produk->update($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk dari database.
     *
     * @param Produk $produk Model Produk dari Route Model Binding
     * @return RedirectResponse
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    // =====================================================================
    //                       METHOD UNTUK SISI PENGGUNA (USER)
    // =====================================================================

    /**
     * Menampilkan halaman galeri produk untuk user.
     *
     * @return View
     */
    public function showToUser(): View
    {
        $produks = Produk::with('kategori')->latest()->get();
        $kategoris = Kategori::all();

        return view('user.produk', compact('produks', 'kategoris'));
    }

    /**
     * Menampilkan halaman detail satu produk untuk user.
     *
     * @param Produk $produk Model Produk dari Route Model Binding
     * @return View
     */
    public function show(Produk $produk): View
    {
        // $produk sudah otomatis diambil berdasarkan ID dari URL
        return view('user.deskripsiproduk', compact('produk'));
    }
}