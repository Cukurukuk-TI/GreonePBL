<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $sayur = Produk::whereHas('kategori', fn ($q) => $q->where('slug', 'sayur'))->get();
        $pupuk = Produk::whereHas('kategori', fn ($q) => $q->where('slug', 'pupuk'))->get();
        $kebun = Produk::whereHas('kategori', fn ($q) => $q->where('slug', 'kebun'))->get();

        return view('produk', compact('sayur', 'pupuk', 'kebun'));
    }
}
