<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\WinnerResource;
use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class WinnerResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_o_status_da_rifa_deve_mudar_quando_um_ganhador_for_criado(): void
    {
        $order = Order::factory()
            ->for(Rifa::factory(['status' => Rifa::STATUS_PUBLISHED]))
            ->hasPayment(['date_approved' => now()])
            ->create([
                'status' => Order::STATUS_PAID,
            ]);

        Livewire::test(WinnerResource\Pages\CreateWinner::class)
            ->fillForm([
                'drawn_number' => $order->numbers_reserved[0],
                'testimonial' => fake()->sentence(),
                'video' => null,
                'position' => 1,
                'rifa_id' => $order->rifa->id,
                'order_id' => $order->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertEquals(Rifa::STATUS_PUBLISHED, $order->rifa->status);
        $order->rifa->refresh();
        $this->assertEquals(Rifa::STATUS_FINISHED, $order->rifa->status);
    }
}
