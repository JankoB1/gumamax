<?php namespace Delmax\Webapp\Providers;

use Delmax\Webapp\DmxWebApp;
use Illuminate\Support\ServiceProvider;

class DmxWebAppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('dmxwebapp', function ($app) {
            return new DmxWebApp(config('dmxwebapp'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['dmxwebapp'];
    }
}
