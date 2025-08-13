<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;
        $type = $faker->randomElement(['vehicle', 'generator', 'other']);

        return [
            'company_id' => Company::factory(),
            'type' => $type,
            'name' => $type === 'vehicle' ? $faker->randomElement(['تويوتا هايلكس', 'هيونداي', 'كيا']) : ($type === 'generator' ? 'مولد ديزل' : 'حاسوب محمول'),
            'serial_number' => $faker->unique()->numerify('SN-########'),
            'model' => $faker->optional()->bothify('M-###'),
            'brand' => $faker->optional()->randomElement(['Hyundai', 'Toyota', 'Dell', 'HP', 'Iveco', 'Huawei']),
            'color' => $faker->optional()->randomElement(['أبيض', 'أسود', 'رمادي', 'أزرق', 'أحمر', 'أخضر']),
            'plate_number' => $type === 'vehicle' ? $faker->optional()->bothify('##-######') : null,
            'maintenance_required' => $faker->boolean(15),
            'employee_id' => null,
        ];
    }
}
