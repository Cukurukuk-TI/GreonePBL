<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriArtikel;
use Illuminate\Support\Str;

class KategoriArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Tips Hidroponik',
            'Panduan Pemula',
            'Info Nutrisi',
            'Berita Pertanian',
        ];

        foreach ($kategoris as $namaKategori) {
            KategoriArtikel::updateOrCreate(
                ['nama' => $namaKategori],
                ['slug' => Str::slug($namaKategori)]
            );
        }
    }
}
