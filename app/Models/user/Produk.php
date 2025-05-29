<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['nama_produk', 'kategori_id', 'harga', 'gambar_url'];
    public $timestamps = true;

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}

