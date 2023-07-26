<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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
        $thumbnailTemp = fake()->image(width: 1280, height: 720);
        $thumbnailPath = Storage::putFile($thumbnailTemp);

        return [
            'title' => fake()->sentences(2, true),
            'thumbnail' => $thumbnailPath,
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
