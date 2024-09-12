<?php

namespace App\Filament\Resources\RifaResource\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class LastOrdersTable extends BaseWidget
{
    public $record = null;

    protected static ?string $heading = 'Pedidos Recentes';

    public function table(Table $table): Table
    {
        $query = Order::query()
            ->where('rifa_id', $this->record->id)
            ->latest();

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('customer_fullname')
                    ->label('Nome do Comprador'),
                TextColumn::make('customer_telephone')
                    ->label('Telefone'),
                TextColumn::make('numbers_reserved')
                    ->label('Qdte. de Números')
                    ->formatStateUsing(fn ($state) => Str::of($state)->explode(',')->count()),
                TextColumn::make('status')
                    ->label('Situação')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => OrderStatus::tryFrom($state)->getLabel())
                    ->color(fn (?string $state) => OrderStatus::tryFrom($state)->getColor()),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (Order $order) => url("/admin/orders/{$order->id}/edit")),
            ]);
    }
}
