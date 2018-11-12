<?php

return [
    'BASE_URL' => '/public',
    'APP_URL' => dirname(dirname(__FILE__)),
    'MAIN_LAYOUT' => 'master',
    'DATABASE' => [
        'SERVER' => 'mysql',
        'HOST' => 'localhost',
        'USERNAME' => 'root',
        'PASSWORD' => '',
        'DATABASE_NAME' => 'fellow_company',
    ],
    'AUTO_LOAD' => [
        'app/main/Route.php',
        'routes/routes.php',
        'helpers/helpers.php',
    ],
];
