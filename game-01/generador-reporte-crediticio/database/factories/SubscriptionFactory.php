<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'document'  => $this->faker->unique()->numerify('########'),
            'email'     => uniqid('user_', true) . '@test.com',
            'phone'     => $this->faker->numerify('9########'),
        ];
    }
}
