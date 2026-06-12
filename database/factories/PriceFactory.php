<?php

namespace Database\Factories;

use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Trénink '.$this->faker->unique()->word(),
            'amount' => $this->faker->numberBetween(1, 50) * 100,
            'period' => '3 měsíce',
            'note' => null,
            'visible' => true,
            'sort' => 0,
        ];
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['visible' => false]);
    }
}
