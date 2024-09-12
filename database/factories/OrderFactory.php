<?php

namespace Database\Factories;

use App\Models\Rifa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_fullname' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_telephone' => fake()->numerify(),
            'numbers_reserved' => $this->generateNumbersReserved(fake()->randomDigitNotNull()),
            'status' => fake()->randomElement(['paid', 'reserved']),
            'rifa_id' => Rifa::factory(),
        ];
    }

    private function generateNumbersReserved(int $total): array
    {
        $numbers = [];

        for ($i = 0; count($numbers) < $total; $i++) {
            $randomNumber = fake()->randomNumber(6);

            if (! array_search($randomNumber, $numbers)) {
                $numbers[] = $randomNumber;
            }
        }

        return $numbers;
    }
}
