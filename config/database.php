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
    'default' => 'mysql',

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
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'novaframe',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'engine' => 'innoDB'
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => 'localhost',
            'port' => 5432,
            'database' => 'novaframe',
            'username' => 'postgres',
            'password' => '',
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => \NovaFrame\Helpers\Path\Path::join(DIR_APP, 'Database', 'database.sqlite'),
        ],
    ],
];
