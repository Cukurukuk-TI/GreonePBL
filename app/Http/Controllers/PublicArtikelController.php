<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class PublicArtikelController extends Controller
{
    /**
     * Menampilkan daftar semua artikel yang sudah di-publish.
     */
    public function index()
    {
        $artikels = Artikel::where('status', 'published')
                           ->latest('tanggal_post')
                           ->paginate(9);

        return view('artikel', compact('artikels'));
    }
}
