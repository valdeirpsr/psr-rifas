<?php

namespace App\Filament\Resources;

use App\Enums\WinnerPosition;
use App\Filament\Resources\WinnerResource\Pages;
use App\Models\Order;
use App\Models\Rifa;
use App\Models\Winner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WinnerResource extends Resource
{
    protected static ?string $model = Winner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rifa_id')
                    ->label(__('filament.input.rifa_id'))
                    ->options(Rifa::all(['title', 'id'])->pluck('title', 'id'))
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('drawn_number')
                    ->label(__('filament.input.drawn_number'))
                    ->hidden(fn (\Filament\Forms\Get $get) => ! $get('rifa_id'))
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('order_id')
                    ->label(__('filament.input.select_order_id'))
                    ->reactive()
                    ->options(function (\Filament\Forms\Get $get) {
                        $drawNumber = '"'.preg_replace('/\D/', '', $get('drawn_number')).'"';

                        return Order::select('customer_fullname', 'id')
                            ->where('rifa_id', $get('rifa_id'))
                            ->whereRaw('JSON_CONTAINS(`numbers_reserved`, ?)', [$drawNumber])
                            ->pluck('customer_fullname', 'id');
                    })
                    ->hidden(fn (\Filament\Forms\Get $get) => ! $get('drawn_number'))
                    ->required(),

                Forms\Components\Select::make('position')
                    ->label(__('filament.input.select_position'))
                    ->hidden(fn (\Filament\Forms\Get $get) => ! $get('order_id'))
                    ->options(WinnerPosition::class)
                    ->required(),

                Forms\Components\Textarea::make('testimonial')
                    ->label(__('filament.input.testimonial'))
                    ->hidden(fn (\Filament\Forms\Get $get) => ! $get('position'))
                    ->maxLength(16777215),

                Forms\Components\FileUpload::make('video')
                    ->label(__('filament.input.testimonial_video'))
                    ->acceptedFileTypes(['video/mp4'])
                    ->hidden(fn (\Filament\Forms\Get $get) => ! $get('position'))
                    ->disk(env('FILAMENT_FILESYSTEM_DISK', config('filesystems.default'))),
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
                    ->formatStateUsing(fn (?string $state) => WinnerPosition::tryFrom($state)->getLabel()),
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

    public static function getModelLabel(): string
    {
        return __('filament.resource.winner');
    }
}
