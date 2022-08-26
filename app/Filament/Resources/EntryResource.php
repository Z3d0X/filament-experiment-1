<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntryResource\Pages;
use App\Models\Entry;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class EntryResource extends Resource
{
    protected static ?string $model = Entry::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('user_id'),
                TextInput::make('title')
                    ->maxLength(255),
                // Textarea::make('content'),
                
                // DateTimePicker::make('published_at'),
                // TextInput::make('status')
                //     ->maxLength(255),

                Group::make([])
                    ->statePath('data')
                    ->schema(fn ($livewire) => static::getFormSchema($livewire->collection->fields)),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('user_id'),
                TextColumn::make('title'),
                // TextColumn::make('content'),
                // TextColumn::make('data'),
                // TextColumn::make('published_at')
                //     ->dateTime(),
                // TextColumn::make('status'),
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
    
    protected static function getFormSchema($fields): array
    {
        return array_map(function (array $field) {
            $config = $field['data'];

            return match ($field['type']) {
                'text' => TextInput::make($config['name'])
                    ->label($config['label'])
                    ->type($config['type'] ?? 'text')
                    ->required($config['is_required']),
                'select' => Select::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required($config['is_required']),
                'checkbox' => Checkbox::make($config['name'])
                    ->label($config['label'])
                    ->required($config['is_required']),
                'textarea' => Textarea::make($config['name'])
                    ->label($config['label'])
                    ->required($config['is_required']),
            };
        }, $fields);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEntries::route('/{collection:slug}'),
        ];
    }    
}
