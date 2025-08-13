<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankAccount>
 */
class BankAccountFactory extends Factory
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
            'account_number' => (string)$faker->unique()->numerify('##########') . $faker->numberBetween(10, 99),
            'bank_name' => $faker->randomElement(['المصرف التجاري الوطني', 'مصرف الجمهورية', 'مصرف الوحدة', 'مصرف الصحارى']),
            'branch_name' => $faker->optional()->city(),
            'account_holder_name' => $faker->optional()->name(),
            'swift_code' => $faker->optional()->swiftBicNumber(),
            'currency' => $faker->randomElement(['LYD', 'USD', 'EUR', 'GBP', 'AED']),
            'account_type' => $faker->randomElement(['normal', 'card', 'other']),
            'active' => $faker->boolean(90),
        ];
    }
}
