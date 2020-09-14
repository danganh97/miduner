<?php

namespace App\Providers;

use Midun\Routing\RouteServiceProvider as ServiceProvider;
use Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Booting route service
     * 
     * @return void
     */
    public function boot(): void
    {
        Route::middleware('web:auth')
            ->namespace('App\\Http\\Controllers')
            ->group(route_path('web.php'))
            ->register();

        Route::middleware('web:api')
            ->prefix('api')
            ->namespace('App\\Http\\Controllers\\Api')
            ->group(route_path('api.php'))
            ->register();
    }
}
