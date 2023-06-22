<?php

namespace App\Filament\Resources\RifaResource\Pages;

use App\Filament\Resources\RifaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRifa extends EditRecord
{
    protected static string $resource = RifaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
