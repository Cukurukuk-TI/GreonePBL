<?php

namespace App\Http\Controllers;

// Import class yang dibutuhkan
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
// Import Form Request yang sudah kita buat
use App\Http\Requests\StorePromoRequest;
use App\Http\Requests\UpdatePromoRequest;

class PromoController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen promo.
     * Menampilkan daftar semua promo dan form untuk menambah promo baru.
     *
     * @return View
     */
    public function index(): View
    {
        // Ambil semua promo, urutkan dari yang terbaru
        $promos = Promo::latest()->get();

        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Menyimpan data promo baru ke database.
     *
     * @param StorePromoRequest $request Data request yang sudah tervalidasi.
     * @return RedirectResponse
     */
    public function store(StorePromoRequest $request): RedirectResponse
    {
        try {
            // Ambil data yang sudah lolos validasi dari Form Request
            Promo::create($request->validated());

            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Menangani kemungkinan error lain saat menyimpan
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan promo.')
                ->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit promo yang sudah ada.
     * Form ditampilkan di halaman yang sama dengan daftar promo.
     *
     * @param Promo $promo Model Promo dari Route Model Binding.
     * @return View
     */
    public function edit(Promo $promo): View
    {
        // Ambil semua promo untuk ditampilkan di tabel
        $promos = Promo::latest()->get();

        // Kirim data promo yang akan diedit ke view dengan nama variabel 'editPromo'
        return view('admin.promos.index', [
            'promos' => $promos,
            'editPromo' => $promo,
        ]);
    }

    /**
     * Memperbarui data promo di database.
     *
     * @param UpdatePromoRequest $request Data request yang sudah tervalidasi.
     * @param Promo $promo Model Promo yang akan diupdate.
     * @return RedirectResponse
     */
    public function update(UpdatePromoRequest $request, Promo $promo): RedirectResponse
    {
        try {
            // Update promo dengan data yang sudah tervalidasi
            $promo->update($request->validated());

            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate promo.')
                ->withInput();
        }
    }

    /**
     * Menghapus promo dari database.
     *
     * @param Promo $promo Model Promo yang akan dihapus.
     * @return RedirectResponse
     */
    public function destroy(Promo $promo): RedirectResponse
    {
        try {
            $promo->delete();
            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus promo.');
        }
    }

    /**
     * Mengubah status promo (aktif/tidak aktif).
     *
     * @param Promo $promo Model Promo yang statusnya akan diubah.
     * @return RedirectResponse
     */
    public function toggleStatus(Promo $promo): RedirectResponse
    {
        try {
            $promo->update(['is_active' => !$promo->is_active]);
            $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()->route('admin.promos.index')
                ->with('success', "Promo '{$promo->nama_promo}' berhasil {$status}!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status promo.');
        }
    }
}