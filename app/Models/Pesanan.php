<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pesanan',
        'user_id',
        'produk_id',
        'promo_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'diskon',
        'ongkos_kirim',
        'pajak',
        'total_harga',
        'alamat_pengiriman',
        'metode_pembayaran',
        'metode_pengiriman',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // Accessor
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedDiskonAttribute()
    {
        return 'Rp' . number_format($this->diskon, 0, ',', '.');
    }

    public function getFormattedOngkosKirimAttribute()
    {
        return 'Rp' . number_format($this->ongkos_kirim, 0, ',', '.');
    }

    public function getTanggalPesananAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    // Method untuk generate kode pesanan
    public static function generateKodePesanan()
    {
        do {
            $kode = 'PSN' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('kode_pesanan', $kode)->exists());
        
        return $kode;
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }
    
}