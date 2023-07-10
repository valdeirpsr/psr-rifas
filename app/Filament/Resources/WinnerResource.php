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
use Illuminate\Support\Facades\Log;

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
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('drawn_number')
                    ->hidden(fn (\Closure $get) => !$get('rifa_id'))
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('order_id')
                    ->reactive()
                    ->options(function (\Closure $get) {
                        $drawNumber = '"' . preg_replace('/\D/', '', $get('drawn_number')) . '"';

                        return Order::select('customer_fullname', 'id')
                            ->where('rifa_id', $get('rifa_id'))
                            ->whereRaw('JSON_CONTAINS(`numbers_reserved`, ?)', [$drawNumber])
                            ->pluck('customer_fullname', 'id');
                    })
                    ->hidden(fn (\Closure $get) => !$get('drawn_number'))
                    ->required(),

                Forms\Components\Select::make('position')
                    ->hidden(fn (\Closure $get) => !$get('order_id'))
                    ->options(self::POSITION)
                    ->required(),

                Forms\Components\Textarea::make('testimonial')
                    ->hidden(fn (\Closure $get) => !$get('position'))
                    ->maxLength(16777215),

                Forms\Components\FileUpload::make('video')
                    ->acceptedFileTypes(['video/mp4'])
                    ->hidden(fn (\Closure $get) => !$get('position')),
            ])
            ->columns(1);
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
