<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;

class KategoriArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriArtikels = KategoriArtikel::latest()->paginate(10);

        return view('admin.kategori-artikel.index', compact('kategoriArtikels'));
    }

    public function create() {

    }
    public function store(Request $request) {

    }
    public function show(KategoriArtikel $kategoriArtikel) {

    }
    public function edit(KategoriArtikel $kategoriArtikel) {

    }
    public function update(Request $request, KategoriArtikel $kategoriArtikel) {

    }
    public function destroy(KategoriArtikel $kategoriArtikel) {

    }
}
