<?php

namespace App\Filament\Resources;

use App\Enums\RifaStatus;
use App\Filament\Resources\RifaResource\Pages;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RifaResource extends Resource
{
    protected static ?string $model = Rifa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        /**
                         * Title
                         */
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->required(),

                        /**
                         * Thumbnail
                         */
                        Forms\Components\FileUpload::make('thumbnail')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->imagePreviewHeight(300)
                            ->required()
                            ->disk(env('FILAMENT_FILESYSTEM_DISK', config('filesystems.default'))),
                    ]),

                /**
                 * Price
                 */
                Forms\Components\TextInput::make('price')
                    ->currencyMask(
                        thousandSeparator: '.',
                        decimalSeparator: ','
                    )
                    ->required(),

                /**
                 * Total de bilhetes disponíveis
                 */
                Forms\Components\TextInput::make('total_numbers_available')
                    ->numeric()
                    ->required(),

                /**
                 * Número máximo de bilhete que dá para comprar de uma só vez
                 */
                Forms\Components\TextInput::make('buy_max')
                    ->default(300)
                    ->required(),

                /**
                 * Número mínimo de bilhete que dá para comprar
                 */
                Forms\Components\TextInput::make('buy_min')
                    ->default(1)
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                /**
                 * Forma de sorteio
                 */
                Forms\Components\Select::make('raffle')
                    ->required()
                    ->options([
                        'Loteria Federal' => 'Loteria Federal',
                    ])
                    ->default('Loteria Federal'),

                /**
                 * Situação
                 */
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(RifaStatus::class)
                    ->default('draft'),

                /**
                 * Data de Expiração
                 */
                Forms\Components\DateTimePicker::make('expired_at')
                    ->timezone('America/Sao_Paulo')
                    ->seconds(false),

                /**
                 * Exibir ranking de compradores
                 */
                Forms\Components\Radio::make('ranking_buyer')
                    ->boolean('Show', 'Hidden')
                    ->gridDirection('row')
                    ->required(),

                /**
                 * Descrição
                 */
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->maxLength(65535),
                    ])
                    ->columns(1),

                /**
                 * Slug
                 */
                Forms\Components\TextInput::make('slug')
                    ->hidden(fn (string $context) => $context !== 'edit')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.column.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('filament.column.price'))
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.column.status'))
                    ->formatStateUsing(fn (?string $state) => RifaStatus::tryFrom($state)->getLabel())
                    ->badge()
                    ->color(fn (?string $state) => RifaStatus::tryFrom($state)->getColor()),
                Tables\Columns\TextColumn::make('expired_at')
                    ->label(__('filament.column.expired_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.column.created_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_expired')
                    ->label(__('filament.filter.is_expired'))
                    ->query(fn (Builder $query): Builder => $query->where('expired_at', '<', now())),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.action.edit')),
                Tables\Actions\ViewAction::make()
                    ->label(__('filament.action.view')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('filament.action.delete')),
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
            'index' => Pages\ListRifas::route('/'),
            'create' => Pages\CreateRifa::route('/create'),
            'edit' => Pages\EditRifa::route('/{record}/edit'),
            'view' => Pages\ViewRifa::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RifaResource\Widgets\OrderStatsOverview::class,
            RifaResource\Widgets\LastOrdersTable::class,
            RifaResource\Widgets\WinnersListOverview::class,
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.resource.rifa');
    }
}
