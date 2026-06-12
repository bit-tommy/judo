<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;

/**
 * Členské příspěvky (podklad od klienta, červen 2026).
 * Idempotentní — páruje podle titulu, lze spouštět opakovaně
 * (i na produkci: `php artisan db:seed --class=PriceSeeder --force`).
 */
class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            ['title' => 'Judo — Praha', 'amount' => 3000, 'period' => '3 měsíce', 'sort' => 1],
            ['title' => 'Judo — Vodochody', 'amount' => 2000, 'period' => '3 měsíce', 'sort' => 2],
            ['title' => 'Taijutsu', 'amount' => 3000, 'period' => '3 měsíce', 'sort' => 3],
        ];

        foreach ($prices as $price) {
            Price::updateOrCreate(['title' => $price['title']], $price);
        }
    }
}
