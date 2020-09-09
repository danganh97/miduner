<?php

namespace Midun\FileSystem;

use Midun\ServiceProvider;

class FileSystemServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fileSystem', function () {
            return new FileSystem();
        });
    }
}
