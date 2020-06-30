<?php

return [
    'server' => env('APP_SERVER', 'windows'),
    'base' => env('APP_BASE',  dirname(dirname(__FILE__))),
    'appurl' => env('APP_URL', '127.0.0.1:8000'),
    'key' => env('APP_KEY', ''),
    'layout' => env('MAIN_LAYOUT', 'master'),
    'autoload' => [
        'main/Route.php',
        'helpers/helpers.php',
        'routes/routes.php',
    ],

    'aliases' => [
        'Route' => Main\Route::class,
        'DB' => Main\QueryBuilder::class,
        'AppException' => Main\Http\Exceptions\AppException::class,
        'Session' => Main\Session::class,
        'Request' => Http\Request::class
    ],
    
    'providers' => [
        App\Providers\AppServiceProvider::class
    ]
];
