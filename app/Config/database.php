<?php

return [

    /*
     |-------------------------------------------
     | Database Driver
     |-------------------------------------------
     |
     | Database Driver Name to use in connect
     |
     */
    'driver' => env('DB_DRIVER', 'mysql'),

    /*
     |-----------------------------------------------------------------------------------
     | Configurations
     |-----------------------------------------------------------------------------------
     |
     | This array contains configuration settings for different database drivers.
     | Each driver configuration should be defined as a key-value pair, where the key
     | represents the driver name and the value is an array of configuration settings
     | specific to that driver.
     |
     */
    'configurations' => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', 3306),
            'username'  => env('DB_USER', 'root'),
            'password'  => env('DB_PASS'),
            'database'  => env('DB_NAME'),
            'charset'   => env('DB_CHAR', 'utf8mb4'),
            'engine'    => env('DB_ENGINE', 'INNODB'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'options'   => [
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        ],

        'postgresql' => [
            'driver'   => 'postgresql',
            'host'     => env('DB_HOST', '127.0.0.1'),
            'port'     => env('DB_PORT', 5432),
            'username' => env('DB_USER', 'postgres'),
            'password' => env('DB_PASS'),
            'database' => env('DB_NAME'),
            'charset'  => env('DB_CHAR', 'utf8'),
            'options'  => [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
            ]
        ],
    ],

];
