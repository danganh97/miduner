<?php

namespace Midun\Translator;

use Midun\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Register 3rd-party services
     */
    public function boot()
    {
        $cache = items_in_folder(base_path('resources/lang'), false);

        foreach ($cache as $item) {
            $key = str_replace('.php', '', $item);
            $value = require base_path("resources/lang/{$item}");

            $this->app->make('translator')->setTranslation($key, $value);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('translator', function () {
            return $this->app->make(\Midun\Translator\Translator::class);
        });
    }
}
