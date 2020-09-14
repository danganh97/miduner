<?php

namespace App\Providers;

use Midun\Http\Request;
use Midun\Http\Validation\ValidationServiceProvider as BaseValidationServiceProvider;

class ValidationServiceProvider extends BaseValidationServiceProvider
{
    /**
     * Booting route service
     * 
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        $validator = $this->app->make('validator');

        $validator->setRule('customRule', function (Request $request): bool {
            $headers = $request->headers();

            return isset($headers->Authorization);
        }, trans('validation.custom.message'));
    }
}
