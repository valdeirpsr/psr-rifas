<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Winner>
 */
class WinnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'drawn_number' => fake()->numberBetween(1000, 9999),
            'testimonial' => null,
            'video' => null,
            'position' => fake()->numberBetween(1, 6),
            'rifa_id' => Rifa::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
