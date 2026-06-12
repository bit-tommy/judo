<?php

namespace Database\Factories;

use App\Models\GalleryAlbum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<GalleryAlbum>
 */
class GalleryAlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Album '.$this->faker->unique()->words(2, true);
        $year = (int) $this->faker->numberBetween(2024, 2026);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'date_label' => 'Červen '.$year,
            'year' => $year,
            'cats' => ['klub'],
            'photos' => $this->faker->numberBetween(1, 40),
            'cover' => null,
        ];
    }
}
