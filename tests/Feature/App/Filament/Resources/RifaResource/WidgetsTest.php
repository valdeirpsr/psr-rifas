<?php

namespace Tests\Feature\App\Filament\Resources\RifaResource;

use App\Enums\OrderStatus;
use App\Enums\RifaStatus;
use App\Filament\Resources\RifaResource\Pages\ViewRifa;
use App\Filament\Resources\RifaResource\Widgets\LastOrdersTable;
use App\Filament\Resources\RifaResource\Widgets\OrderStatsOverview;
use App\Filament\Resources\RifaResource\Widgets\StatsOverview;
use App\Models\Order;
use App\Models\Rifa;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WidgetsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valida as informações de estatísticas do widget OrderStats
     */
    public function test_renderizacao_do_widget_order_stats_overview(): void
    {
        $rifa = Rifa::factory()->createOneQuietly([
            'status' => RifaStatus::PUBLISHED,
            'price' => 0.4,
            'total_numbers_available' => 100,
        ]);

        Order::factory()->createManyQuietly([
            [
                'status' => OrderStatus::PAID,
                'rifa_id' => $rifa->id,
                'numbers_reserved' => range(1, 30),
            ],
            [
                'status' => OrderStatus::RESERVED,
                'rifa_id' => $rifa->id,
                'numbers_reserved' => range(31, 48),
            ]
        ]);

        Livewire::test(OrderStatsOverview::class, [
            'rifa' => $rifa,
        ])
            ->assertSeeText('Total de bilhetes pagos')
            ->assertSeeText('30')
            ->assertSeeText('Total de bilhetes pendentes')
            ->assertSeeText('18')
            ->assertSeeText('Total de bilhetes disponíveis')
            ->assertSeeText('52');
    }

    /**
     * Valida as informações de estatísticas do widget StatsOverview
     */
    public function test_renderizacao_do_widget_stats_overview(): void
    {
        $rifa = Rifa::factory()->createOneQuietly([
            'status' => RifaStatus::PUBLISHED,
            'price' => 0.4,
            'total_numbers_available' => 100,
        ]);

        Order::factory()->createManyQuietly([
            [
                'status' => OrderStatus::PAID,
                'rifa_id' => $rifa->id,
                'numbers_reserved' => range(1, 30),
            ],
            [
                'status' => OrderStatus::RESERVED,
                'rifa_id' => $rifa->id,
                'numbers_reserved' => range(31, 48),
            ]
        ]);

        Livewire::test(StatsOverview::class, [
            'rifa' => $rifa,
        ])
            ->assertSeeText('Total Vendido')
            ->assertSeeText('R$ 12,00')
            ->assertSeeText('Total de bilhetes pendentes')
            ->assertSeeText('R$ 7,20')
            ->assertSeeText('Total de bilhetes disponíveis')
            ->assertSeeText('52,00%');
    }

    /**
     * Valida as informações da lista de pedidos recentes
     */
    public function test_renderizacao_dos_pedidos_recentes(): void
    {
        $rifa = Rifa::factory()->createOneQuietly([
            'status' => RifaStatus::PUBLISHED,
            'price' => 0.4,
            'total_numbers_available' => 100,
        ]);

        $orders = Order::factory()->createManyQuietly([
            [
                'status' => OrderStatus::PAID,
                'rifa_id' => $rifa->id,
                'numbers_reserved' => range(1, 30),
            ],
            [
                'status' => OrderStatus::RESERVED,
                'rifa_id' => $rifa->id,
                'numbers_reserved' => range(31, 48),
            ]
        ]);

        $anothersOrders = Order::factory()->count(4)->createQuietly();

        Livewire::test(LastOrdersTable::class, [
            'record' => $rifa,
        ])
            ->assertCanSeeTableRecords($orders)
            ->assertCanNotSeeTableRecords($anothersOrders)
            ->assertCountTableRecords(2)
            ->assertCanRenderTableColumn('id')
            ->assertCanRenderTableColumn('customer_fullname')
            ->assertCanRenderTableColumn('customer_telephone')
            ->assertCanRenderTableColumn('numbers_reserved')
            ->assertCanRenderTableColumn('status');
    }
}
