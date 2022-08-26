<?php

namespace App\Filament\Resources\EntryResource\Pages;

use App\Filament\Resources\EntryResource;
use App\Models\Collection;
use Closure;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ManageEntries extends ListRecords
{
    protected static string $resource = EntryResource::class;

    public ?Collection $collection;

    protected function getTableQuery(): Builder
    {
        return static::getResource()::getEloquentQuery()
            ->where(function (Builder $query) {
                $query->where('collection_id', $this->collection->id);
            });
    }

    protected function getTitle(): string
    {
        return $this->collection->name;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['collection_id'] = $this->collection->id;
                    return $data;
                }),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }


    protected function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        return [];
    }
}
