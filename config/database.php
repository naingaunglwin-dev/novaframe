<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work.
    |
    */
    'default' => env('DB_DRIVER', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | You can configure multiple database types: MySQL, PostgreSQL, SQLite.
    |
    */
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_NAME', 'novaframe'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS'),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'engine' => env('DB_ENGINE', 'InnoDB'),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 5432),
            'database' => env('DB_NAME', 'novaframe'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS'),
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_NAME', \NovaFrame\Helpers\Path\Path::join(DIR_APP, 'Database', 'database.sqlite')),
        ],
    ],
];
