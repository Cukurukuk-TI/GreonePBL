<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'alamat',
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
        'deleted_at' => 'datetime',
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

    // app/Models/User.php
    public function alamats()
    {
        return $this->hasMany(Alamat::class);
    }
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }

    // Tambahkan relasi testimoni
    public function testimonis()
    {
        return $this->hasMany(Testimoni::class);
    }
}
