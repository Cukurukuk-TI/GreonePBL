<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = [
            [
                'nama_kategori' => 'Sayuran Daun',
                'nama_produk' => 'Selada Romaine Hidroponik',
                'deskripsi_produk' => 'Selada Romaine segar dan renyah, ditanam dengan metode hidroponik tanpa pestisida. Cocok untuk salad dan sandwich.',
                'harga_produk' => 15000,
                'stok_produk' => 100,
                'gambar_produk' => 'images/produks/selada.jpg',
            ],
            [
                'nama_kategori' => 'Sayuran Daun',
                'nama_produk' => 'Bayam Hijau Hidroponik',
                'deskripsi_produk' => 'Bayam hijau segar kaya akan zat besi. Ideal untuk ditumis, dibuat sup, atau jus sehat.',
                'harga_produk' => 12000,
                'stok_produk' => 150,
                'gambar_produk' => 'images/produks/bayam.jpg',
            ],
            [
                'nama_kategori' => 'Sayuran Buah',
                'nama_produk' => 'Tomat Ceri Hidroponik',
                'deskripsi_produk' => 'Tomat ceri manis dan juicy, pilihan sempurna untuk camilan sehat, salad, atau hiasan masakan.',
                'harga_produk' => 25000,
                'stok_produk' => 80,
                'gambar_produk' => 'images/produks/tomat_ceri.jpg',
            ],
            [
                'nama_kategori' => 'Herbal & Rempah',
                'nama_produk' => 'Daun Mint Hidroponik',
                'deskripsi_produk' => 'Daun mint dengan aroma menyegarkan, cocok untuk minuman, teh, atau sebagai bumbu masakan.',
                'harga_produk' => 10000,
                'stok_produk' => 200,
                'gambar_produk' => 'images/produks/mint.jpg',
            ],
        ];

        foreach ($produks as $produkData) {
            // Cari kategori berdasarkan nama untuk mendapatkan ID-nya
            $kategori = Kategori::where('nama_kategori', $produkData['nama_kategori'])->first();

            if ($kategori) {
                Produk::updateOrCreate(
                    ['nama_produk' => $produkData['nama_produk']], // Kunci untuk mencari produk yang ada
                    [
                        'id_kategori' => $kategori->id, // Nama kolom yang benar
                        'deskripsi_produk' => $produkData['deskripsi_produk'], // Nama kolom yang benar
                        'harga_produk' => $produkData['harga_produk'],
                        'stok_produk' => $produkData['stok_produk'],
                        'gambar_produk' => $produkData['gambar_produk'],
                    ]
                );
            }
        }
    }
}
