<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportLoan>
 */
class ReportLoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank' => fake()->company(),
            'status' => fake()->randomElement(['NOR', 'CPP', 'DEF', 'PER']),
            'currency' => 'PEN',
            'amount' => fake()->randomFloat(2, 1000, 50000),
            'expiration_days' => fake()->numberBetween(0, 120),
        ];
    }
}
