<?php

// app/Models/Produk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'nama_produk',
        'deskripsi_produk',
        'stok_produk',
        'harga_produk',
        'gambar_produk'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Tambahkan di dalam class Produk
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class);
    }
    
    // Tambahkan relasi testimoni
    public function testimonis()
    {
        return $this->hasMany(Testimoni::class);
    }

    public function approvedTestimonis()
    {
        return $this->hasMany(Testimoni::class)->where('status', 'approved');
    }


}
