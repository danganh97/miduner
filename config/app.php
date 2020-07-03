<?php

return [
    'server' => env('APP_SERVER', 'windows'),
    'base' => env('APP_BASE',  dirname(dirname(__FILE__))),
    'url' => env('APP_URL', '127.0.0.1:8000'),
    'key' => env('APP_KEY', ''),
    'layout' => env('MAIN_LAYOUT', 'master'),
    'autoload' => [
        'main/Route.php',
        'helpers/helpers.php',
        'routes/routes.php',
    ],

    'aliases' => [
        'App' => Main\App::class,
        'Route' => Main\Route::class,
        'DB' => Main\Supports\Facades\QueryBuilder::class,
        'AppException' => Main\Http\Exceptions\AppException::class,
        'Session' => Main\Supports\Facades\Session::class,
        'Request' => Main\Supports\Facades\Request::class,
        'Repository' => Main\Supports\Facades\Repository::class,
        'Hash' => Main\Supports\Facades\Hash::class,
        'Auth' => Main\Supports\Facades\Auth::class,
    ],
    
    'providers' => [
        Main\Services\Providers\EntityServiceProvider::class,
        Main\Hashing\HashServiceProvider::class,
        Main\Auth\AuthenticationServiceProvider::class,

        App\Providers\AppServiceProvider::class,
        App\Providers\RepositoryServiceProvider::class,
    ]
];
