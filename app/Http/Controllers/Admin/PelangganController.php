<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalPelangganAktif = User::where('role', 'user')->count();
        $totalPelangganDihapus = User::onlyTrashed()->where('role', 'user')->count(); // Jika Anda menggunakan Soft Deletes

        $pelanggan = User::where('role', 'user')->latest()->paginate(10);
        return view('admin.pelanggan.index', compact('pelanggan', 'totalPelangganAktif', 'totalPelangganDihapus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
        */
    public function destroy(User $pelanggan) // Laravel akan otomatis menemukan User berdasarkan ID
    {
        // Validasi agar tidak bisa menghapus role admin
        if ($pelanggan->role === 'admin') {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Tidak dapat menghapus akun admin.');
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

        /**
     * Menampilkan daftar pelanggan yang sudah di-soft delete.
     */
    public function trash()
    {
        $pelanggan = User::onlyTrashed()->where('role', 'user')->latest()->paginate(10);
        return view('admin.pelanggan.trash', compact('pelanggan'));
    }

    /**
     * Memulihkan pelanggan yang sudah di-soft delete.
     */
    public function restore($id)
    {
        $pelanggan = User::onlyTrashed()->where('role', 'user')->findOrFail($id);
        $pelanggan->restore();

        return redirect()->route('admin.pelanggan.trash')->with('success', 'Pelanggan berhasil dipulihkan.');
    }

    /**
     * Menghapus pelanggan secara permanen dari database.
     */
    public function forceDelete($id)
    {
        $pelanggan = User::onlyTrashed()->where('role', 'user')->findOrFail($id);
        $pelanggan->forceDelete();

        return redirect()->route('admin.pelanggan.trash')->with('success', 'Pelanggan berhasil dihapus secara permanen.');
    }

}
