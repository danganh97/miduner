<?php

namespace Midun\Http\Validation;

use Midun\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $validator = $this->app->make('validator');
        $validator->setRules([
            'required',
            'min',
            'max',
            'number',
            'string',
            'file',
            'image',
            'video',
            'audio',
            'email',
            'unique'
        ]);
    }

    public function register()
    {
        $this->app->singleton('validator', function () {
            return new Validator();
        });
    }
}
