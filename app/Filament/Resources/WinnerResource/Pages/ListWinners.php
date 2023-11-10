<?php

namespace App\Filament\Resources\WinnerResource\Pages;

use App\Filament\Resources\WinnerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWinners extends ListRecords
{
    protected static string $resource = WinnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.action.create', ['label' => $this->getModelLabel()])),
        ];
    }
}
