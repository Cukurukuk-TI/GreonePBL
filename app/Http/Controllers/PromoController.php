<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::orderBy('created_at', 'desc')->get();
        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promos.create');
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
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        $promos = Promo::orderBy('created_at', 'desc')->get();
        $editPromo = $promo;

        return view('admin.promos.index', compact('promos', 'editPromo'));
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
     * Toggle the active status of a promo.
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
