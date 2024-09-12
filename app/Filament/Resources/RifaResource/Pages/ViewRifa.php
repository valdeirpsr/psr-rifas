<?php

namespace App\Filament\Resources\RifaResource\Pages;

use App\Filament\Resources\RifaResource;
use App\Services\RifaService;
use Filament\Resources\Pages\ViewRecord;

class ViewRifa extends ViewRecord
{
    protected static string $resource = RifaResource::class;

    protected static string $view = 'filament.resources.rifa-resource.pages.view-rifa';

    protected function getHeaderWidgets(): array
    {
        return [
            RifaResource\Widgets\OrderStatsOverview::make([
                'rifa' => $this->getRecord(),
            ]),
            RifaResource\Widgets\WinnersListOverview::make([
                'rifa' => $this->getRecord(),
            ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RifaResource\Widgets\LastOrdersTable::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|string|array
    {
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
