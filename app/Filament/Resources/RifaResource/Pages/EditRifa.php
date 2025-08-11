<?php

namespace App\Filament\Resources\RifaResource\Pages;

use App\Filament\Resources\RifaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRifa extends EditRecord
{
    protected static string $resource = RifaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
