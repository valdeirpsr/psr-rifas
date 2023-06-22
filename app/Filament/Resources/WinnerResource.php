<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WinnerResource\Pages;
use App\Filament\Resources\WinnerResource\RelationManagers;
use App\Models\Order;
use App\Models\Rifa;
use App\Models\Winner;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WinnerResource extends Resource
{
    protected static ?string $model = Winner::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 2;

    private const POSITION = [
        1 => '1º Prêmio',
        2 => '2º Prêmio',
        3 => '3º Prêmio',
        4 => '4º Prêmio',
        5 => '5º Prêmio',
        6 => '6º Prêmio',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rifa_id')
                    ->options(Rifa::all(['title', 'id'])->pluck('title', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn (\Closure $set, $state) => $set('order_id', $state)),

                Forms\Components\Select::make('order_id')
                    ->reactive()
                    ->options(fn (\Closure $get) =>
                        Order::select('customer_fullname', 'id')
                            ->where('rifa_id', $get('rifa_id'))
                            ->pluck('customer_fullname', 'id')
                    )
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('position')
                    ->options(self::POSITION)
                    ->required(),

                Forms\Components\TextInput::make('drawn_number')
                    ->required()
                    ->maxLength(10),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Textarea::make('testimonial')
                            ->maxLength(16777215),

                        Forms\Components\FileUpload::make('video')
                            ->acceptedFileTypes(['video/mp4']),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rifa.title')
                    ->label(__('filament.column.title')),
                Tables\Columns\TextColumn::make('order.customer_fullname')
                    ->label(__('filament.column.fullname')),
                Tables\Columns\TextColumn::make('drawn_number')
                    ->label(__('filament.column.drawn_number')),
                Tables\Columns\TextColumn::make('testimonial')
                    ->label(__('filament.column.testimonial')),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('filament.column.position'))
                    ->enum(self::POSITION)
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWinners::route('/'),
            'create' => Pages\CreateWinner::route('/create'),
            'edit' => Pages\EditWinner::route('/{record}/edit'),
        ];
    }
}
