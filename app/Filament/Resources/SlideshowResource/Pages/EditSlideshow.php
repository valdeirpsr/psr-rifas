<?php

namespace App\Filament\Resources\SlideshowResource\Pages;

use App\Filament\Resources\SlideshowResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSlideshow extends EditRecord
{
    protected static string $resource = SlideshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
