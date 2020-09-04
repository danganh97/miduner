<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => '127.0.0.1',
            'port' => 3456,
            'database' => 'hello',
            'username' => 'postgres',
            'password' => 'root',
        ],
        'backup' => [
            'driver' => env('BACKUP_DB_CONNECTION', 'mysql'),
            'host' => env('BACKUP_DB_HOST', '127.0.0.1'),
            'port' => env('BACKUP_DB_PORT', '3306'),
            'database' => env('BACKUP_DB_DATABASE', 'forge'),
            'username' => env('BACKUP_DB_USERNAME', 'forge'),
            'password' => env('BACKUP_DB_PASSWORD', ''),
        ],
    ],
];
