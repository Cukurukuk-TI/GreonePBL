<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use App\Models\User;

class AlamatController extends Controller
{
    // Tampilkan semua alamat user, dengan pencarian berdasarkan nama penerima
    public function index(Request $request)
    {
        $query = Alamat::where('user_id', auth()->id());

        if ($request->has('search')) {
            $query->where('nama_penerima', 'like', '%' . $request->search . '%');
        }

        $alamats = $query->latest()->get();

        return view('alamat.index', compact('alamats'));
    }

    // Tampilkan form tambah alamat
    public function create()
    {
        return view('alamat.create');
    }

    // Simpan alamat baru
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|in:rumah,kantor,other',
            'nama_penerima' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'detail_alamat' => 'required|string|max:1000',
        ]);

        Alamat::create([
            'user_id' => auth()->id(),
            'label' => $request->label,
            'nama_penerima' => $request->nama_penerima,
            'nomor_hp' => $request->nomor_hp,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'detail_alamat' => $request->detail_alamat,
        ]);

        return redirect()->route('alamat.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    // Tampilkan form edit alamat
    public function edit($id)
    {
        $alamat = Alamat::where('user_id', auth()->id())->findOrFail($id);

        return view('alamat.edit', compact('alamat'));
    }

    // Update alamat
    public function update(Request $request, $id)
    {
        $alamat = Alamat::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'label' => 'required|in:rumah,kantor,other',
            'nama_penerima' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'detail_alamat' => 'required|string|max:1000',
        ]);

        $alamat->update([
            'label' => $request->label,
            'nama_penerima' => $request->nama_penerima,
            'nomor_hp' => $request->nomor_hp,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'detail_alamat' => $request->detail_alamat,
        ]);

        return redirect()->route('alamat.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    // Hapus alamat
    public function destroy($id)
    {
        $alamat = Alamat::where('user_id', auth()->id())->findOrFail($id);
        $alamat->delete();

        return redirect()->route('alamat.index')->with('success', 'Alamat berhasil dihapus.');
    }
}
