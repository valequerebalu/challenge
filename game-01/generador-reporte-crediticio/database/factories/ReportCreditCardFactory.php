<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Currency;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportCreditCard>
 */
class ReportCreditCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $line = fake()->randomFloat(2, 2000, 20000);
        return [
            'bank' => fake()->company(),
            'currency' => 'PEN',
            'line' => $line,
            'used' => fake()->randomFloat(2, 0, $line),
        ];
    }
}
