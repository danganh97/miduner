<?php

namespace Midun\Bus;

use Midun\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Register 3rd-party services
     */
    public function boot()
    { }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\Midun\Contracts\Bus\Dispatcher::class, \Midun\Bus\Dispatcher::class);
    }
}
