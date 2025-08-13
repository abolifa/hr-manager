<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;
        $members = collect(range(1, $faker->numberBetween(2, 6)))
            ->map(fn() => $faker->name())->values()->all();

        return [
            'name' => $faker->unique()->company(),
            'english_name' => $faker->optional()->company(),
            'law_shape' => $faker->optional()->randomElement(['شركة مساهمة', 'شركة ذات مسؤولية محدودة', 'مكتب خدمات']),
            'phone' => $faker->unique()->numerify('091#######'),
            'email' => $faker->unique()->safeEmail(),
            'ceo' => $faker->name(),
            'members' => $members,
            'capital' => $faker->optional()->randomFloat(2, 10_000, 5_000_000),
            'established_at' => $faker->optional()->date(),
            'address' => $faker->address(),
            'logo' => null,
            'letterhead' => null,
        ];
    }
}
