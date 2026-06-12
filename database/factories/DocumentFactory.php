<?php

namespace Database\Factories;

use App\Enums\DocumentGroup;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title($this->faker->unique()->words(2, true));

        return [
            'title' => $title,
            'meta' => $this->faker->sentence(3),
            'group' => DocumentGroup::Prihlasky,
            'type' => 'file',
            'filename' => Str::slug($title).'.pdf',
            'url' => null,
            'size_bytes' => $this->faker->numberBetween(50_000, 2_000_000),
            'downloads' => 0,
            'visible' => true,
            'sort' => 0,
        ];
    }

    /** Externí odkaz (bez souboru). */
    public function external(): static
    {
        return $this->state(fn () => [
            'group' => DocumentGroup::Externi,
            'type' => 'external',
            'filename' => null,
            'url' => $this->faker->url(),
            'size_bytes' => null,
        ]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['visible' => false]);
    }
}
