<?php

namespace App\Filament\Resources\RifaResource\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\RifaResource\Pages\ViewRifa;
use App\Models\Order;
use App\Models\Rifa;
use App\Services\RifaService;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    public Rifa $rifa;

    protected function getStats(): array
    {
        $rifaService = app(RifaService::class);

        $paid = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::PAID);
        $reserved = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::RESERVED);
        $available = number_format($this->rifa->total_numbers_available - ($paid + $reserved), 0, ',', '.');

        return [
            Stat::make('Total de bilhetes pagos', $paid),
            Stat::make('Total de bilhetes pendentes', $reserved),
            Stat::make('Total de bilhetes disponíveis', $available),
        ];
    }
}
