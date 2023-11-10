<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 1;

    private const STATUS = [
        'archived' => 'Arquivado',
        'expired' => 'Expirado',
        'paid' => 'Pago',
        'reserved' => 'Reservado',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rifa_id')
                    ->required()
                    ->options(Rifa::all(['title', 'id'])->pluck('title', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('customer_fullname')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('customer_email')
                    ->email()
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('customer_telephone')
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) =>
                        $mask->pattern('(00) 00000-0000')
                    )
                    ->required()
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TagsInput::make('numbers_reserved')
                    ->rules([
                        fn() => fn(string $attribute, $values, \Closure $fail) =>
                            !collect($values)->every(fn (string $value) =>
                                !!preg_match('/^\d+$/', $value) !== false
                            ) && $fail('Informe apenas números')
                    ]),
                Forms\Components\Select::make('status')
                    ->default('reserved')
                    ->required()
                    ->options(self::STATUS),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rifa.title')
                    ->label(__('filament.column.rifa')),
                Tables\Columns\TextColumn::make('customer_fullname')
                    ->label(__('filament.column.fullname'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_telephone')
                    ->label(__('filament.column.telephone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('numbers_reserved')
                    ->label(__('filament.column.numbers'))
                    ->searchable()
                    ->words(3),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.column.status'))
                    ->enum(self::STATUS),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y \à\s H:i')
                    ->label(__('filament.column.created_at')),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_paid')
                    ->label(__('filament.filter.is_paid'))
                    ->query(fn (Builder $query): Builder => $query->where('status', 'paid')),

                Tables\Filters\Filter::make('is_reserved')
                    ->label(__('filament.filter.is_reserved'))
                    ->query(fn (Builder $query): Builder => $query->where('status', 'reserved')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.action.edit')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.resource.order');
    }
}
