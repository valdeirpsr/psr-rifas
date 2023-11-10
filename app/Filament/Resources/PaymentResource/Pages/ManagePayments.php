<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePayments extends ManageRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.action.create', ['label' => $this->getModelLabel()])),
        ];
    }
}
