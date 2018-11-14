<?php

return [
    'BASE_URL' => env('BASE_URL', '/public'),
    'APP_URL' => env('APP_URL', dirname(dirname(__FILE__))),
    'MAIN_LAYOUT' => env('MAIN_LAYOUT', 'master'),
    'DATABASE' => [
        'DB_CONNECTION' => env('DB_CONNECTION', 'mysql'),
        'DB_HOST' => env('DB_HOST', 'localhost'),
        'DB_PORT' => env('DB_PORT', 'root'),
        'DB_DATABASE' => env('DB_DATABASE', 'fellow_company'),
        'DB_USERNAME' => env('DB_USERNAME', 'root'),
        'DB_PASSWORD' => env('DB_PASSWORD', '')
    ],
    'AUTO_LOAD' => [
        'app/main/Route.php',
        'routes/routes.php',
        'helpers/helpers.php',
    ],
];
