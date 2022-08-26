<?php

namespace App\Providers;

use App\Filament\Resources\EntryResource;
use App\Models\Collection;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Collection::query()
                ->get(['id','name','slug'])
                ->each(function ($collection) {
                    //TODO: auth
                    Filament::registerNavigationItems([
                        NavigationItem::make()
                            ->label($collection->name)
                            ->url(EntryResource::getUrl('index', ['collection' => $collection]))
                            ->icon('heroicon-o-cube')
                            ->group('Entries')
                            ->isActiveWhen(function () use ($collection) {
                                return request()->routeIs('filament.resources.entries.*') && request()->route('collection')->is($collection);
                            }),
                    ]);
                });
        });
    }
}
