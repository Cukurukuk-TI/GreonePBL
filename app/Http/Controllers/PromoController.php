<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $promos = Promo::orderBy('created_at', 'desc')->get();
        $editPromo = null;

        // Jika ada parameter edit, ambil data promo untuk diedit
        if ($request->has('edit')) {
            $editPromo = Promo::find($request->get('edit'));
        }

        return view('admin.promos.index', compact('promos', 'editPromo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_promo' => 'required|string|max:255',
            'deskripsi_promo' => 'required|string',
            'besaran_potongan' => 'required|integer|min:1|max:100',
            'minimum_belanja' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'nama_promo.required' => 'Nama promo harus diisi',
            'deskripsi_promo.required' => 'Deskripsi promo harus diisi',
            'besaran_potongan.required' => 'Besaran potongan harus diisi',
            'besaran_potongan.min' => 'Besaran potongan minimal 1%',
            'besaran_potongan.max' => 'Besaran potongan maksimal 100%',
            'minimum_belanja.required' => 'Minimum belanja harus diisi',
            'minimum_belanja.min' => 'Minimum belanja tidak boleh negatif',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Promo::create($request->all());
            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan promo.')
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo)
    {
        $validator = Validator::make($request->all(), [
            'nama_promo' => 'required|string|max:255',
            'deskripsi_promo' => 'required|string',
            'besaran_potongan' => 'required|integer|min:1|max:100',
            'minimum_belanja' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'nama_promo.required' => 'Nama promo harus diisi',
            'deskripsi_promo.required' => 'Deskripsi promo harus diisi',
            'besaran_potongan.required' => 'Besaran potongan harus diisi',
            'besaran_potongan.min' => 'Besaran potongan minimal 1%',
            'besaran_potongan.max' => 'Besaran potongan maksimal 100%',
            'minimum_belanja.required' => 'Minimum belanja harus diisi',
            'minimum_belanja.min' => 'Minimum belanja tidak boleh negatif',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $promo->update($request->all());
            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate promo.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
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
     * Toggle promo status (active/inactive)
     */
    public function toggleStatus(Promo $promo)
    {
        try {
            $promo->update(['is_active' => !$promo->is_active]);
            $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('admin.promos.index')
                ->with('success', "Promo berhasil {$status}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status promo.');
        }
    }
}