<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Akce '.$this->faker->unique()->words(3, true);
        $starts = $this->faker->dateTimeBetween('+1 week', '+6 months');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'starts_on' => $starts->format('Y-m-d'),
            'ends_on' => null,
            'place' => $this->faker->city(),
            'note' => null,
            'description' => $this->faker->optional()->paragraph(),
            'is_main' => false,
        ];
    }

    /** Hlavní akce sezóny. */
    public function main(): static
    {
        return $this->state(fn () => ['is_main' => true]);
    }

    /** Vícedenní akce. */
    public function multiDay(int $days = 6): static
    {
        return $this->state(function (array $attributes) use ($days) {
            $start = Carbon::parse($attributes['starts_on']);

            return ['ends_on' => $start->copy()->addDays($days)->format('Y-m-d')];
        });
    }

    /** Akce, která už proběhla. */
    public function past(): static
    {
        return $this->state(fn () => [
            'starts_on' => $this->faker->dateTimeBetween('-2 years', '-1 week')->format('Y-m-d'),
            'ends_on' => null,
        ]);
    }
}
