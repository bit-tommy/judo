<?php

namespace Database\Factories;

use App\Enums\MemberGroup;
use App\Enums\MemberStatus;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName().' '.$this->faker->lastName(),
            'age' => $this->faker->numberBetween(5, 15),
            'group' => $this->faker->randomElement(MemberGroup::cases()),
            'parent_name' => $this->faker->firstName().' '.$this->faker->lastName(),
            'phone' => '+420 '.$this->faker->numerify('### ### ###'),
            'email' => $this->faker->optional(0.7)->safeEmail(),
            'member_since' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'belt' => $this->faker->randomElement(['Bílý pás · 6. kyu', 'Žlutý pás · 5. kyu', 'Oranžový pás · 4. kyu', null]),
            'status' => MemberStatus::Aktivni,
            'note' => null,
        ];
    }

    /** Nová přihláška čekající na schválení. */
    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => MemberStatus::Nova,
            'member_since' => null,
            'belt' => null,
        ]);
    }

    public function awaitingPayment(): static
    {
        return $this->state(fn () => ['status' => MemberStatus::Plat]);
    }
}
