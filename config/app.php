<?php

return [
    'server' => env('APP_SERVER', 'windows'),
    'baseurl' => env('BASE_URL', '/public'),
    'appurl' => env('APP_URL', dirname(dirname(__FILE__))),
    'layout' => env('MAIN_LAYOUT', 'master'),
    'autoload' => [
        'app/main/Route.php',
        'helpers/common.php',
        'helpers/helpers.php',
        'routes/routes.php',
    ],
];
