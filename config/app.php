<?php

return [
    'server' => env('APP_SERVER', 'windows'),
    'base' => env('APP_BASE',  dirname(dirname(__FILE__))),
    'appurl' => env('APP_URL', '127.0.0.1:8000'),
    'key' => env('APP_KEY', ''),
    'layout' => env('MAIN_LAYOUT', 'master'),
    'autoload' => [
        'app/Main/Route.php',
        'helpers/helpers.php',
        'routes/routes.php',
    ],

    'aliases' => [
        'Route' => App\Main\Route::class,
        'DB' => App\Main\QueryBuilder::class,
        'AppException' => App\Main\Http\Exceptions\AppException::class,
        'Session' => App\Main\Session::class,
        'Request' => App\Http\Request::class
    ]
];
