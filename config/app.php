<?php

return [
    'base' => env('APP_BASE',  dirname(dirname(__FILE__))),
    'url' => env('APP_URL', '127.0.0.1:8000'),
    'key' => env('APP_KEY', ''),
    'layout' => env('MAIN_LAYOUT', 'master'),
    'autoload' => [
        'helpers/helpers.php',
        'routes/routes.php',
    ],

    'aliases' => [
        'App' => Main\Supports\Facades\App::class,
        'Route' => Main\Supports\Facades\Route::class,
        'DB' => Main\Supports\Facades\QueryBuilder::class,
        'AppException' => Main\Http\Exceptions\AppException::class,
        'Session' => Main\Supports\Facades\Session::class,
        'Request' => Main\Supports\Facades\Request::class,
        'Hash' => Main\Supports\Facades\Hash::class,
        'Auth' => Main\Supports\Facades\Auth::class,
    ],
    
    'providers' => [
        Main\Hashing\HashServiceProvider::class,
        Main\Auth\AuthenticationServiceProvider::class,
        Main\Database\QueryBuilder\QueryBuilderServiceProvider::class,
        Main\Routing\RouteServiceProvider::class,
        Main\Http\RequestServiceProvider::class,
        Main\Routing\Controller\ControllerServiceProvider::class,
        Main\Supports\Response\DataResponseServiceProvider::class,
        Main\Session\SessionServiceProvider::class,
        Main\Database\Connections\ConnectionServiceProvider::class,
        Main\Routing\RouteServiceProvider::class,

        App\Providers\AppServiceProvider::class,
        App\Providers\RepositoryServiceProvider::class,
    ]
];
