<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;
        return [
            'company_id' => Company::factory(),
            'name' => $faker->name(),
            'phone' => $faker->unique()->numerify('091#######'),
            'email' => $faker->unique()->safeEmail(),
            'role' => $faker->randomElement(['employee', 'accountant', 'driver', 'manager', 'sales', 'hr', 'supervisor']),
            'photo' => null,
            'password' => Hash::make('091091'),
            'remember_token' => Str::random(10),
        ];
    }
}
