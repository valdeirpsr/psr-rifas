<?php

namespace App\Filament\Resources\RifaResource\Widgets;

use App\Models\Rifa;
use App\Services\RifaService;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class WinnersListOverview extends Widget
{
    protected int|string|array $columnSpan = 2;

    protected static string $view = 'filament.resources.rifa-resource.widgets.winners-list-overview';

    protected static ?string $heading = 'Pedidos Recentes';

    public Rifa $rifa;

    protected function getColumns(): int
    {
        return 1;
    }

    public function getWinners(): Collection
    {
        return (new RifaService)->winners($this->rifa);
    }

    public function hasWinner(): bool
    {
        return (new RifaService)->hasWinner($this->rifa);
    }
}
