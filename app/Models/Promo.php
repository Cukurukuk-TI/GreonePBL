<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_promo',
        'deskripsi_promo',
        'besaran_potongan',
        'minimum_belanja',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'minimum_belanja' => 'decimal:2'
    ];

    /**
     * Check if promo is currently active and valid
     */
    public function isValid()
    {
        // $now = Carbon::now()->toDateString();
        // return $this->is_active &&
        //     $this->tanggal_mulai <= $now &&
        //     $this->tanggal_selesai >= $now;

        $now = now(); // ini akan menjadi objek Carbon, bukan string
        return $this->is_active &&
            $this->tanggal_mulai <= $now &&
            $this->tanggal_selesai >= $now;
    }

    /**
     * Get formatted minimum belanja
     */
    public function getFormattedMinimumBelanjaAttribute()
    {
        return 'Rp' . number_format($this->minimum_belanja, 0, ',', '.');
    }

    /**
     * Get formatted besaran potongan
     */
    public function getFormattedBesaranPotonganAttribute()
    {
        return $this->besaran_potongan . '%';
    }

    /**
     * Scope for active promos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid promos (active and within date range)
     */
    public function scopeValid($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where('is_active', true)
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now);
    }
}