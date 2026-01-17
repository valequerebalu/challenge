<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportOtherDebt>
 */
class ReportOtherDebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entity' => fake()->company(),
            'currency' => 'PEN',
            'amount' => fake()->randomFloat(2, 100, 5000),
            'expiration_days' => fake()->numberBetween(0, 30),
        ];
    }
}
