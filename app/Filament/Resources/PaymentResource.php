<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required(),
                Forms\Components\Select::make('order_id')
                    ->required()
                    ->searchable()
                    ->options(fn () => Order::all('id')
                        ->pluck('id', 'id')
                    ),
                Forms\Components\TextInput::make('ticket_url')
                    ->required()
                    ->maxLength(255)
                    ->url(),
                Forms\Components\TextInput::make('payment_code')
                    ->required()
                    ->maxLength(40),
                Forms\Components\DateTimePicker::make('date_of_expiration')
                    ->required()
                    ->timezone('America/Sao_Paulo')
                    ->default(now()),
                Forms\Components\TextInput::make('transaction_amount')
                    ->currencyMask(
                        thousandSeparator: '.',
                        decimalSeparator: ','
                    )
                    ->required(),
                Forms\Components\TextInput::make('qr_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date_approved')
                    ->timezone('America/Sao_Paulo')
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.column.payment_id'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.customer_fullname')
                    ->label(__('filament.column.fullname'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_code')
                    ->label(__('filament.column.payment_code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_amount')
                    ->label(__('filament.column.transaction_amount'))
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('date_of_expiration')
                    ->label(__('filament.column.expiration'))
                    ->dateTime('d/m/Y \à\s H:i'),
                Tables\Columns\TextColumn::make('date_approved')
                    ->label(__('filament.column.approved'))
                    ->dateTime('d/m/Y \à\s H:i')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_approved')
                    ->query(fn (Builder $query) => $query->where('date_approved', '!=', null)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.action.edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('filament.action.delete')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePayments::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.resource.payment');
    }
}
