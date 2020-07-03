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
        'App' => Main\Application::class,
        'Route' => Main\Route::class,
        'DB' => Main\Database\QueryBuilder\QueryBuilder::class,
        'AppException' => Main\Http\Exceptions\AppException::class,
        'Session' => Main\Session::class,
        'Request' => App\Http\Requests\Request::class,
        'Repository' => Main\Supports\Patterns\Abstracts\AppRepository::class,
        'Hash' => Main\Hashing\BcryptHasher::class,
        'Auth' => Main\Auth\Authenticatable::class
    ],
    
    'providers' => [
        Main\Services\Providers\EntityServiceProvider::class,
        Main\Hashing\HashServiceProvider::class,
        Main\Auth\AuthenticationServiceProvider::class,

        App\Providers\AppServiceProvider::class,
        App\Providers\RepositoryServiceProvider::class,
    ]
];
