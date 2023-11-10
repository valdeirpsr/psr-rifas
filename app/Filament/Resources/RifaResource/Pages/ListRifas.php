<?php

namespace App\Filament\Resources\RifaResource\Pages;

use App\Filament\Resources\RifaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRifas extends ListRecords
{
    protected static string $resource = RifaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.action.create', ['label' => $this->getModelLabel()])),
        ];
    }
}
