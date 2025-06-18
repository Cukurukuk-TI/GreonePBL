<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'gambar',
        'author',
        'tanggal_post',
        'kategori_artikel_id',
        'konten',
        'status',
    ];

    protected $casts = [
        'tanggal_post' => 'date',
    ];

    public function kategoriArtikel()
    {
        return $this->belongsTo(KategoriArtikel::class);
    }
}
