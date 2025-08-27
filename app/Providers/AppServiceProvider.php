<?php

namespace App\Providers;

use App\Events\DetailGetPerformed;
use App\Events\SearchQueryPerformed;
use App\Listeners\SaveDetailGet;
use App\Listeners\SaveSearchQuery;
use App\Services\StarWarsApi;
use Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StarWarsApi::class, function () {
            return new StarWarsApi(config('services.star_wars.api.base_url'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            SearchQueryPerformed::class,
            SaveSearchQuery::class
        );


        Event::listen(
            DetailGetPerformed::class,
            SaveDetailGet::class
        );
    }
}
