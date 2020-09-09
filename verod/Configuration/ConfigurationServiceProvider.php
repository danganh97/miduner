<?php

namespace Midun\Configuration;

use Midun\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Register 3rd-party services
     */
    public function boot()
    {
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('config', function () {
            return $this->app->make('config');
        });
    }
}
