<?php

namespace App\Filament\Resources\RifaResource\Widgets;

use App\Enums\OrderStatus;
use App\Models\Rifa;
use App\Services\RifaService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

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

        $paidTotal = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::PAID);
        $reservedTotal = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::RESERVED);
        $totalReservedAndPaid = $paidTotal + $reservedTotal;

        $totalPaid = $this->formatNumber($paidTotal * $this->rifa->price, 'currency');
        $totalReserved = $this->formatNumber($reservedTotal * $this->rifa->price, 'currency');
        $percent = $this->formatNumber(100 - ($totalReservedAndPaid / $this->rifa->total_numbers_available) * 100, 'percentage');

        return [
            Stat::make('Total Vendido', $totalPaid),
            Stat::make('Total de bilhetes pendentes', $totalReserved),
            Stat::make('Total de bilhetes disponíveis', $percent),
        ];
    }

    /**
     * Formata os números de acordo com o tipo
     *
     * @param  int|float  $value
     * @param  string  $type
     * @return mixed
     */
    private function formatNumber($value, $type)
    {
        $flags = match ($type) {
            'currency' => [
                'in' => 'BRL',
            ],
            'percentage' => [
                'precision' => 2,
            ]
        };

        $flags['locale'] = config('app.locale');

        return Number::{$type}($value, ...$flags);
    }
}
