<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RifaResource\Pages;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RifaResource extends Resource
{
    protected static ?string $model = Rifa::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 0;

    private const STATUS = [
        'draft' => 'Rascunho',
        'published' => 'Publicar',
        'archived' => 'Archivar',
        'finished' => 'Finalizada'
    ];

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
                            ->disk(fn (): string => config('filesystems.default'))
                    ]),

                /**
                 * Price
                 */
                Forms\Components\TextInput::make('price')
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                        ->patternBlocks([
                            'money' => fn (Forms\Components\TextInput\Mask $mask) => $mask
                                ->numeric()
                                ->thousandsSeparator('')
                                ->decimalSeparator('.'),
                        ])
                        ->pattern('money')
                    )
                    ->required(),

                /**
                 * Total de bilhetes disponíveis
                 */
                Forms\Components\TextInput::make('total_numbers_available')
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                        ->numeric()
                        ->integer()
                        ->thousandsSeparator('.')
                        ->minValue(1)
                        ->maxValue(100_000)
                    )
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
                    ->options(self::STATUS)
                    ->default('draft'),

                /**
                 * Data de Expiração
                 */
                Forms\Components\DateTimePicker::make('expired_at')
                    ->timezone('America/Sao_Paulo')
                    ->withoutSeconds(),

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
                    ->disabled()
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
                    ->enum(self::STATUS),
                Tables\Columns\TextColumn::make('expired_at')
                    ->label(__('filament.column.expired_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.column.created_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.column.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_expired')
                    ->label(__('filament.filter.is_expired'))
                    ->query(fn (Builder $query): Builder => $query->where('expired_at', '<', now()))
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.action.edit')),
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
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.resource.rifa');
    }
}
