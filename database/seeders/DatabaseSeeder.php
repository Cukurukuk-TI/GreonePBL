<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(UserSeeder::class);
        $this->call(PromoSeeder::class);
        $this->call(KategoriSeeder::class);
        $this->call(ProdukSeeder::class);
        $this->call(KategoriArtikelSeeder::class);
        $this->call(ArtikelSeeder::class);
    }
}
