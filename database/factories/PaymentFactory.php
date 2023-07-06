<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->randomNumber(),
            'ticket_url' => fake()->url(),
            'payment_code' => 'pix',
            'date_of_expiration' => now()->addHours(24),
            'transaction_amount' => fake()->randomFloat(2),
            'qr_code' => fake()->sentence(),
            'date_approved' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'date_approved' => now(),
        ]);
    }
}
