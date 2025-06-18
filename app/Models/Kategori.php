<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'gambar_kategori',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_kategori');
    }
}
