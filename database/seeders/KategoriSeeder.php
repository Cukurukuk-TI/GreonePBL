<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Sayuran Daun', 
                'deskripsi' => 'Berbagai jenis sayuran daun segar hidroponik.',
                'gambar_kategori' => 'images/kategori/sayuran-daun.jpg' // Path gambar contoh
            ],
            [
                'nama_kategori' => 'Sayuran Buah', 
                'deskripsi' => 'Berbagai jenis sayuran buah segar hidroponik.',
                'gambar_kategori' => 'images/kategori/sayuran-buah.jpg'
            ],
            [
                'nama_kategori' => 'Herbal & Rempah', 
                'deskripsi' => 'Berbagai jenis herbal dan rempah segar hidroponik.',
                'gambar_kategori' => 'images/kategori/herbal.jpg'
            ],
        ];

        foreach ($kategoris as $kategori) {
            // Menggunakan updateOrCreate untuk menghindari duplikat saat seeder dijalankan ulang
            Kategori::updateOrCreate(
                ['nama_kategori' => $kategori['nama_kategori']],
                $kategori
            );
        }
    }
}
