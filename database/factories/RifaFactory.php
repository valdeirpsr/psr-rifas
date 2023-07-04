<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rifa>
 */
class RifaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentences(2, true),
            'thumbnail' => 'J7P03ilHENBApaoVcUCX2dovCHqyON-metacG5nLnBuZw==-.png',
            'price' => fake()->randomFloat(2, 0.1, 2),
            'description' => fake()->paragraphs(3, true),
            'slug' => 'rifa-publicada-' . fake()->uuid(),
            'total_numbers_available' => 100000,
            'buy_max' => 300,
            'buy_min' => fake()->randomDigitNotNull(),
            'raffle' => 'Loteria Federal',
            'status' => fake()->randomElement(['published', 'draft']),
            'expired_at' => fake()->randomElement([null, now()->addMonth(1)]),
        ];
    }
}
