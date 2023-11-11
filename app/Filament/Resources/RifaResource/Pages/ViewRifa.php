<?php

namespace App\Filament\Resources\RifaResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\RifaResource;
use App\Models\Order;
use App\Services\RifaService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ViewRifa extends ViewRecord
{
    protected static string $resource = RifaResource::class;

    protected static string $view = 'filament.resources.rifa-resource.pages.view-rifa';

    protected function getHeaderWidgets(): array
    {
        return [
            RifaResource\Widgets\OrderStatsOverview::make([
                'rifa' => $this->getRecord()
            ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RifaResource\Widgets\LastOrdersTable::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|string|array {
        return 1;
    }

    /**
     * Retorna o ranking de pessoas que mais comprou bilhetes
     */
    public function rankingBuyers()
    {
        return (new RifaService())->getRanking($this->getRecord());
    }
}
