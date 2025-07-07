<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'admin')
            ->select('users.name') // Kita hanya butuh nama
            ->first();

        $authorName = $adminUser ? $adminUser->name : 'Admin Greone';

        $artikels = [
            [
                'nama_kategori' => 'Panduan Pemula',
                'judul' => '5 Langkah Mudah Memulai Hidroponik di Rumah',
                'konten' => 'Hidroponik adalah solusi modern untuk berkebun tanpa tanah. Berikut adalah 5 langkah mudah untuk Anda yang ingin memulai...',
                'gambar' => 'images/artikels/hidroponik_pemula.jpg',
            ],
            [
                'nama_kategori' => 'Tips Hidroponik',
                'judul' => 'Cara Meracik Nutrisi AB Mix yang Tepat',
                'konten' => 'Nutrisi AB Mix adalah kunci keberhasilan hidroponik. Komposisi yang tepat akan membuat tanaman tumbuh subur. Pelajari cara meraciknya di sini...',
                'gambar' => 'images/artikels/ab_mix.jpg',
            ],
        ];

        foreach ($artikels as $artikelData) {
            $kategori = KategoriArtikel::where('nama', $artikelData['nama_kategori'])->first();

            if ($kategori) {
                Artikel::updateOrCreate(
                    ['judul' => $artikelData['judul']],
                    [
                        'slug' => Str::slug($artikelData['judul']),
                        'gambar' => $artikelData['gambar'],
                        'author' => $authorName, // Sesuai dengan kolom 'author' di migrasi Anda
                        'tanggal_post' => now(),
                        'kategori_artikel_id' => $kategori->id,
                        'konten' => $artikelData['konten'],
                        'status' => 'published',
                    ]
                );
            }
        }
    }
}
