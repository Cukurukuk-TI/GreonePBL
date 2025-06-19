<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'detail_pesanans';

    /**
     * Kolom yang bisa diisi secara massal.
     */
    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    /**
     * Menonaktifkan timestamps (created_at, updated_at) jika tidak diperlukan.
     * Biarkan jika Anda ingin melacak kapan detail pesanan dibuat/diubah.
     */
    // public $timestamps = false;

    /**
     * Relasi ke model Pesanan.
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Relasi ke model Produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
