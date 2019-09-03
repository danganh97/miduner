<?php

return [
    'server' => env('APP_SERVER', 'windows'),
    'baseurl' => env('BASE_URL', '/public'),
    'appurl' => env('APP_URL', dirname(dirname(__FILE__))),
    'layout' => env('MAIN_LAYOUT', 'master'),
    'autoload' => [
        'app/Main/Route.php',
        'helpers/common.php',
        'helpers/helpers.php',
        'routes/routes.php',
    ],

    'aliases' => [
        'Route' => App\Main\Route::class,
        'DB' => App\Main\QueryBuilder::class,
        'AppException' => App\Main\AppException::class,
        'Session' => App\Main\Session::class,
        'Request' => App\Http\Request::class
    ]
];
