<?php

namespace App\Http\Controllers;

// Import class yang dibutuhkan
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
// Import Form Request yang sudah kita buat
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;

class KategoriController extends Controller
{
    /**
     * Menampilkan halaman utama untuk manajemen kategori di sisi admin.
     * Halaman ini menampilkan form tambah/edit dan tabel daftar kategori.
     *
     * @return View
     */
    public function index(): View
    {
        // Mengambil semua kategori beserta jumlah produk terkait (`produks_count`)
        $kategoris = Kategori::withCount('produks')->latest()->get();
    
        return view('admin.kategori.index', compact('kategoris'));
    }
    
    /**
     * Menampilkan halaman kategori di sisi pengguna (user).
     *
     * @return View
     */
    public function indexUser(): View
    {
        $kategoris = Kategori::withCount('produks')->get();
        return view('home', compact('kategoris'));
    }

    /**
     * Menyimpan data kategori baru ke dalam database.
     *
     * @param StoreKategoriRequest $request Data request yang sudah tervalidasi.
     * @return RedirectResponse
     */
    public function store(StoreKategoriRequest $request): RedirectResponse
    {
        // Mengambil data yang sudah lolos validasi dari Form Request
        $validatedData = $request->validated();

        // Cek jika ada file gambar yang diunggah
        if ($request->hasFile('gambar_kategori')) {
            // Simpan gambar ke 'storage/app/public/kategori' dan simpan path-nya
            $validatedData['gambar_kategori'] = $request->file('gambar_kategori')->store('kategori', 'public');
        }

        // Buat record baru di database
        Kategori::create($validatedData);

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     * Data kategori yang akan diedit dilempar ke view yang sama dengan index.
     *
     * @param Kategori $kategori Model Kategori yang didapat dari route model binding.
     * @return View
     */
    public function edit(Kategori $kategori): View
    {
        // Mengambil semua kategori untuk ditampilkan di tabel
        $kategoris = Kategori::withCount('produks')->latest()->get();

        // Mengirim data kategori yang akan diedit ($kategori) dan daftar semua kategori ($kategoris)
        return view('admin.kategori.index', compact('kategori', 'kategoris'));
    }

    /**
     * Memperbarui data kategori yang ada di database.
     *
     * @param UpdateKategoriRequest $request Data request yang sudah tervalidasi.
     * @param Kategori $kategori Model Kategori yang akan diupdate.
     * @return RedirectResponse
     */
    public function update(UpdateKategoriRequest $request, Kategori $kategori): RedirectResponse
    {
        // Mengambil data yang sudah lolos validasi
        $validatedData = $request->validated();

        // Cek jika ada file gambar baru yang diunggah
        if ($request->hasFile('gambar_kategori')) {
            // Hapus gambar lama jika ada untuk menghemat storage
            if ($kategori->gambar_kategori && Storage::disk('public')->exists($kategori->gambar_kategori)) {
                Storage::disk('public')->delete($kategori->gambar_kategori);
            }
            // Unggah gambar baru dan simpan path-nya
            $validatedData['gambar_kategori'] = $request->file('gambar_kategori')->store('kategori', 'public');
        }

        // Update data kategori di database
        $kategori->update($validatedData);

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus data kategori dari database.
     *
     * @param Kategori $kategori Model Kategori yang akan dihapus.
     * @return RedirectResponse
     */
    public function destroy(Kategori $kategori): RedirectResponse
    {
        // Hapus file gambar terkait dari storage sebelum menghapus record database
        if ($kategori->gambar_kategori && Storage::disk('public')->exists($kategori->gambar_kategori)) {
            Storage::disk('public')->delete($kategori->gambar_kategori);
        }

        // Hapus record kategori dari database
        $kategori->delete();

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}