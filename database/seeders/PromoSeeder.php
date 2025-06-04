<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promo;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = [
            [
                'nama_promo' => 'Diskon Sayur Segar',
                'deskripsi_promo' => 'Dapatkan diskon 15% untuk semua pembelian sayuran segar dengan minimum belanja Rp 50.000',
                'besaran_potongan' => 15,
                'minimum_belanja' => 50000,
                'tanggal_mulai' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->addDays(25),
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Promo Buah Lokal',
                'deskripsi_promo' => 'Nikmati potongan harga 20% untuk buah-buahan lokal pilihan dengan minimal pembelian Rp 75.000',
                'besaran_potongan' => 20,
                'minimum_belanja' => 75000,
                'tanggal_mulai' => Carbon::now()->addDays(3),
                'tanggal_selesai' => Carbon::now()->addDays(33),
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Weekend Special',
                'deskripsi_promo' => 'Promo khusus akhir pekan dengan diskon 10% untuk semua produk organik',
                'besaran_potongan' => 10,
                'minimum_belanja' => 30000,
                'tanggal_mulai' => Carbon::now()->next(Carbon::SATURDAY),
                'tanggal_selesai' => Carbon::now()->next(Carbon::SUNDAY),
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Promo Berakhir',
                'deskripsi_promo' => 'Promo yang sudah berakhir untuk testing tampilan status',
                'besaran_potongan' => 25,
                'minimum_belanja' => 100000,
                'tanggal_mulai' => Carbon::now()->subDays(30),
                'tanggal_selesai' => Carbon::now()->subDays(5),
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Promo Nonaktif',
                'deskripsi_promo' => 'Promo yang dinonaktifkan untuk testing status',
                'besaran_potongan' => 30,
                'minimum_belanja' => 150000,
                'tanggal_mulai' => Carbon::now(),
                'tanggal_selesai' => Carbon::now()->addDays(15),
                'is_active' => false,
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}