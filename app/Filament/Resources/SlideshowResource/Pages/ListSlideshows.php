<?php

namespace App\Filament\Resources\SlideshowResource\Pages;

use App\Filament\Resources\SlideshowResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSlideshows extends ListRecords
{
    protected static string $resource = SlideshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.action.create', ['label' => $this->getModelLabel()])),
        ];
    }
}
