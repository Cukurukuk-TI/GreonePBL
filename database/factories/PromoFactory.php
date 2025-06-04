<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalMulai = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $tanggalSelesai = $this->faker->dateTimeBetween($tanggalMulai, '+2 months');
        
        return [
            'nama_promo' => $this->faker->words(3, true) . ' Promo',
            'deskripsi_promo' => $this->faker->paragraph(2),
            'besaran_potongan' => $this->faker->numberBetween(5, 50),
            'minimum_belanja' => $this->faker->randomElement([25000, 50000, 75000, 100000, 150000, 200000]),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'is_active' => $this->faker->boolean(80), // 80% chance to be active
        ];
    }

    /**
     * Create an active promo
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Create an inactive promo
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a currently valid promo (active and within date range)
     */
    public function valid(): static
    {
        $now = Carbon::now();
        
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'tanggal_mulai' => $now->copy()->subDays(rand(1, 7)),
            'tanggal_selesai' => $now->copy()->addDays(rand(7, 30)),
        ]);
    }

    /**
     * Create an expired promo
     */
    public function expired(): static
    {
        $endDate = Carbon::now()->subDays(rand(1, 30));
        
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'tanggal_mulai' => $endDate->copy()->subDays(rand(7, 30)),
            'tanggal_selesai' => $endDate,
        ]);
    }

    /**
     * Create a future promo
     */
    public function future(): static
    {
        $startDate = Carbon::now()->addDays(rand(1, 15));
        
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $startDate->copy()->addDays(rand(7, 30)),
        ]);
    }
}