<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearExpiredOrdersCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_pedidos_expirados_devem_ser_eliminados(): void
    {
        $removed = Order::factory()->for(Rifa::factory())->create([
            'status' => Order::STATUS_RESERVED,
            'expire_at' => now()->subHour(1),
        ]);

        $notRemoved = Order::factory()->for(Rifa::factory())->create([
            'status' => Order::STATUS_RESERVED,
            'expire_at' => now(),
        ]);

        $paid = Order::factory()->for(Rifa::factory())->create([
            'status' => Order::STATUS_PAID,
            'expire_at' => null,
        ]);

        $this->assertDatabaseCount('orders', 3);
        $this->artisan('app:clear-expired-orders-command');

        $this->assertDatabaseMissing('orders', ['id' => $removed->id]);
        $this->assertDatabaseHas('orders', ['id' => $notRemoved->id]);
        $this->assertDatabaseHas('orders', ['id' => $paid->id]);
    }
}
