<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            'Delmax\User\Activity\Interfaces\UserActivityRepositoryInterface',
            'Delmax\User\Activity\Repositories\DbUserActivityRepository'
        );

        $this->app->bind(
            'Gumamax\Products\Repositories\ProductRepositoryInterface',
            'Gumamax\Products\Repositories\EsProductRepository'
        );

        $this->app->bind(
            'Gumamax\Vehicles\Michelin\Repositories\MichelinVehiclesRepositoryInterface',
            'Gumamax\Vehicles\Michelin\Repositories\EsMichelinVehiclesRepository'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
