<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Models\Collection;
use Closure;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        // TextInput::make('entry_table')
                        //     ->required()
                        //     ->maxLength(255),
                        // Toggle::make('active')
                        //     ->required(),
                    ])
                    ->columns(),

                Section::make('Fields')
                    ->schema([
                        Builder::make('fields')
                            ->blocks([
                                Block::make('text')
                                    ->label('Text input')
                                    ->icon('heroicon-o-annotation')
                                    ->schema([
                                        static::getFieldNameInput(),
                                        Checkbox::make('is_required'),
                                    ]),
                                Block::make('select')
                                    ->icon('heroicon-o-selector')
                                    ->schema([
                                        static::getFieldNameInput(),
                                        KeyValue::make('options')
                                            ->addButtonLabel('Add option')
                                            ->keyLabel('Value')
                                            ->valueLabel('Label'),
                                        Checkbox::make('is_required'),
                                    ]),
                                Block::make('checkbox')
                                    ->icon('heroicon-o-check-circle')
                                    ->schema([
                                        static::getFieldNameInput(),
                                        Checkbox::make('is_required'),
                                    ]),
                            ])
                            ->createItemButtonLabel('Add field')
                            ->disableLabel(),
                    ]),
                // Textarea::make('options'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
                // TextColumn::make('entry_table'),
                BooleanColumn::make('active'),
                // TextColumn::make('fields'),
                // TextColumn::make('options'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected static function getFieldNameInput(): Grid
    {
        return Grid::make()
            ->schema([
                TextInput::make('name')
                    ->lazy()
                    ->afterStateUpdated(function (Closure $set, $state) {
                        $label = Str::of($state)
                            ->kebab()
                            ->replace(['-', '_'], ' ')
                            ->ucfirst();

                        $set('label', $label);
                    })
                    ->required(),
                TextInput::make('label')
                    ->required(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }    
}
