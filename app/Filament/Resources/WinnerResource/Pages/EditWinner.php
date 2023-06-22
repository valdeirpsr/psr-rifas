<?php

namespace App\Filament\Resources\WinnerResource\Pages;

use App\Filament\Resources\WinnerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWinner extends EditRecord
{
    protected static string $resource = WinnerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
