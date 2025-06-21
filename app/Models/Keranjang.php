<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Auto calculate subtotal when saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($keranjang) {
            $keranjang->subtotal = $keranjang->jumlah * $keranjang->harga_satuan;
        });
    }
}