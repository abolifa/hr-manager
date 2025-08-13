<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
        ]);

        $companies = Company::factory()
            ->count(5)
            ->create();

        $companies->each(function (Company $company) {
            BankAccount::factory()->count(fake()->numberBetween(2, 3))
                ->for($company)->create();
        });

        $companies->each(function (Company $company) {
            $employees = Employee::factory()->count(fake()->numberBetween(6, 12))
                ->for($company)->create();

            $employees->each(function (Employee $e) {
                EmployeeProfile::factory()->for($e)->create();
            });

            Asset::factory()->count(fake()->numberBetween(4, 10))
                ->for($company)
                ->create()
                ->each(function (Asset $asset) use ($employees) {
                    if (fake()->boolean(60)) {
                        $asset->employee_id = $employees->random()->id;
                        $asset->save();
                    }
                });
        });
        Recipient::factory()->count(12)->create();
    }
}
