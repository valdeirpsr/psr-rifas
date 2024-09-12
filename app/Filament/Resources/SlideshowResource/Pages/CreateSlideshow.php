<?php

namespace App\Filament\Resources\SlideshowResource\Pages;

use App\Filament\Resources\SlideshowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSlideshow extends CreateRecord
{
    protected static string $resource = SlideshowResource::class;

    protected function getRedirectUrl(): string
    {
        return SlideshowResource::getUrl();
    }
}
