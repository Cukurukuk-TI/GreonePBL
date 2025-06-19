<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artikels = Artikel::with('kategoriArtikel')->latest()->paginate(10);

        return view('admin.artikel.index', compact('artikels'));
    }

    public function create() {

    }
    public function store(Request $request) {

    }
    public function show(Artikel $artikel) {

    }
    public function edit(Artikel $artikel) {

    }
    public function update(Request $request, Artikel $artikel) {

    }
    public function destroy(Artikel $artikel) {

    }
}
