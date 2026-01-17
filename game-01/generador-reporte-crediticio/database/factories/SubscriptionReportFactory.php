<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Subscription;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionReport>
 */
class SubscriptionReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween(
            Carbon::create(2025, 1, 10),
            now()
        );

        return [
            'subscription_id' => Subscription::factory(), // Se sobreescribe en el Job
            'period' => fake()->date('Y-m'),
            'created_at' => $date,
        ];
    }
}
