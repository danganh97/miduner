<?php

namespace Midun\View;

use Midun\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    { }

    public function register()
    {
        $this->app->singleton('view', function () {
            $directory = base_path('resources/views');
            $cacheDirectory = cache_path('resources/views');
            return new \Midun\View\View($directory, $cacheDirectory);
        });
    }
}
