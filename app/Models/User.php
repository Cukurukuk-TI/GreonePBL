<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // jangan lupa tambahkan ini agar bisa diisi mass assignable
        'jenis_kelamin',
        'tanggal_lahir',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class);
    }

    // Method untuk mendapatkan total item di keranjang
    public function getTotalKeranjangAttribute()
    {
        return $this->keranjangs()->sum('jumlah');
    }

    // Method untuk mendapatkan total harga keranjang
    public function getTotalHargaKeranjangAttribute()
    {
        return $this->keranjangs()->sum('subtotal');
    }
}
