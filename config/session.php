<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Session Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default session "driver" that will be used on
    | requests. Supported drivers include: "native", "cookie", "file", "redis".
    | Choose the one that best fits your application’s needs.
    |
    */
    'driver' => env('SESSION_DRIVER', 'native'),

    /*
    |--------------------------------------------------------------------------
    | Session Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of the session cookie used to identify a session
    | instance by ID. You can change this to any value that is unique to your
    | application to avoid conflicts with other applications on the same domain.
    |
    */
    'name' => env('SESSION_NAME', 'novaframe'),

    /*
    |--------------------------------------------------------------------------
    | Session Domain
    |--------------------------------------------------------------------------
    |
    | This value defines the domain that the session cookie is available to.
    | You can set this to a specific domain or subdomain to restrict the cookie
    | scope, or leave it null to default to the current domain of the request.
    | Setting this correctly helps control cookie sharing across subdomains.
    |
    */
    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Session Prefix
    |--------------------------------------------------------------------------
    |
    | This value is used to prefix all session keys to avoid name collisions
    | when using shared storage mechanisms like Redis.
    |
    */
    'prefix' => env('SESSION_PREFIX', 'nova_'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of seconds that you wish the session
    | to be allowed to remain idle before it expires. By default, it's one week.
    |
    */
    'expire' => env('SESSION_EXPIRE', 604800), // 1 week

    /*
    |--------------------------------------------------------------------------
    | Secure Cookies
    |--------------------------------------------------------------------------
    |
    | This option determines if cookies should only be sent over HTTPS connections.
    | It's recommended to enable this in production when using HTTPS.
    |
    */
    'secure' => env('SESSION_SECURE', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Only
    |--------------------------------------------------------------------------
    |
    | When this setting is enabled, JavaScript will not be able to access the
    | value of the session cookie. It helps to reduce the risk of XSS attacks.
    |
    */
    'httponly' => env('SESSION_HTTPONLY', false),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | This option determines how your cookies behave when cross-site requests
    | take place. Supported values: "Lax", "Strict", "None".
    |
    */
    'samesite' => env('SESSION_SAMESITE', 'Strict'),

    /*
    |--------------------------------------------------------------------------
    | Session Path
    |--------------------------------------------------------------------------
    |
    | This value determines the path for which the session cookie is available.
    | By default, it is set to "/", making the cookie available across the
    | entire domain. You can limit it to a specific path if needed.
    |
    | Example:
    | - '/' makes the session available site-wide.
    | - '/admin' restricts it to URLs starting with /admin.
    |
    */
    'session_path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | File Storage Path
    |--------------------------------------------------------------------------
    |
    | If the "file" session driver is used, this option determines where session
    | files will be stored. A sensible default has been provided for you.
    |
    */
    'write_path' => env('SESSION_WRITE_PATH', DIR_STORAGE . 'session'),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option determines whether the session data should be encrypted before
    | being stored by the session driver. Encrypting session data enhances security
    | by protecting sensitive information from being read if storage is accessed
    | directly. It is recommended to enable this in production environments.
    |
    | During development, you may choose to disable encryption for easier debugging.
    |
    | ⚠️ IMPORTANT:
    | If you change this value after sessions have already been stored, previously
    | saved data may become unreadable (decryption errors may occur).
    | To avoid issues, run the following command to clear existing session data:
    |
    |     php nova session:clear
    |
    | Supported values:
    | - true: Encrypt session data.
    | - false: Store session data as plain text.
    |
    */
    'encrypt' => true,

    /*
    |--------------------------------------------------------------------------
    | Redis Configuration
    |--------------------------------------------------------------------------
    |
    | These settings are used when the "redis" session driver is selected.
    | You can choose between two Redis clients:
    |
    | - "phpredis": A native C extension for PHP (faster, more efficient).
    | - "predis": A pure PHP Redis client (easier to install and more flexible).
    |
    | Make sure the corresponding PHP extension or package is available.
    |
    | The host, port, and password fields define how to connect to your Redis server.
    | The "database" allows selecting which Redis DB index to use.
    | The "timeout" sets how long to wait (in seconds) for a connection before failing.
    |
    | Example cloud Redis setup:
    | REDIS_HOST=redis-12345.c123.ap-region-1-1.ec2.redns.redis-cloud.com
    | REDIS_PORT=12345
    | REDIS_PASSWORD=yourpassword
    | REDIS_TIMEOUT=1
    |
    */
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
        'password' => env('REDIS_PASSWORD'),
        'database' => env('REDIS_DATABASE', 0),
        'timeout' => env('REDIS_TIMEOUT', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Table for Session Storage
    |--------------------------------------------------------------------------
    |
    | This option specifies the name of the database table used to store session data
    | when using the database session driver. Your custom database session driver
    | will read and write session information to this table.
    |
    | Typical table structure includes columns such as:
    | - `id` (string, primary key) — ID
    | - `session_id` (string) — session ID
    | - `payload` (text or longtext) — serialized session data
    | - `last_activity` (integer or timestamp) — timestamp of last session update for expiration
    |
    | Make sure to create this table in your database before enabling the database session driver.
    |
    */
    'table' => 'session'
];
