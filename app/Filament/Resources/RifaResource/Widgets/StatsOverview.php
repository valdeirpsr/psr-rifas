<?php

namespace App\Filament\Resources\RifaResource\Widgets;

use Akaunting\Money\Money;
use App\Enums\OrderStatus;
use App\Filament\Resources\RifaResource\Pages\ViewRifa;
use App\Models\Order;
use App\Models\Rifa;
use App\Services\RifaService;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    public Rifa $rifa;

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $rifaService = app(RifaService::class);

        $paid = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::PAID);
        $reserved = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::RESERVED);
        $percent = number_format(($paid / $this->rifa->total_numbers_available) * 100, 3);

        $totalPaid = Money::BRL($paid * $this->rifa->price);
        $totalReserved = Money::BRL($reserved * $this->rifa->price);

        return [
            Stat::make('Total Vendido', $totalPaid),
            Stat::make('Total de bilhetes pendentes', $totalReserved),
            Stat::make('Total de bilhetes disponíveis', "{$percent}%"),
        ];
    }
}
