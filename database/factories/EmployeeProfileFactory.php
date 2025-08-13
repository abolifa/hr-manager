<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmployeeProfile>
 */
class EmployeeProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;
        $start = $faker->optional()->dateTimeBetween('-5 years', 'now');
        $end = $faker->boolean(20) ? $faker->dateTimeBetween($start ?: '-2 years', '+1 years') : null;

        return [
            'employee_id' => Employee::factory(),
            'gender' => $faker->optional()->randomElement(['male', 'female']),
            'marital_status' => $faker->optional()->randomElement(['single', 'married']),
            'date_of_birth' => $faker->optional()->date('Y-m-d', '-18 years'),
            'start_date' => $start ? $start->format('Y-m-d') : null,
            'end_date' => $end ? $end->format('Y-m-d') : null,
            'nationality' => $faker->optional()->country(),
            'license' => null,
            'birth_certificate' => null,
            'passport' => null,
            'id_card' => null,
        ];
    }
}
