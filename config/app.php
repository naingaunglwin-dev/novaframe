<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. It is used when the
    | framework needs to place the application's name in notifications,
    | titles, or other display locations as required.
    |
    */
    'name' => env('APP_NAME', 'NovaFrame'),

    /*
    |--------------------------------------------------------------------------
    | Application Base URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the framework when generating URLs through
    | helpers or services. You should set this to the root of your application.
    |
    */
    'base_url' => env('APP_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the environment your application is running in.
    | This may influence how you configure various services the app uses.
    | Common values: "production", "development", "staging", etc.
    |
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by PHP date and date-time functions. A sensible default
    | has been set to UTC.
    |
    */
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the encryption and security components of the
    | framework. It should be a random, 32-character string.
    |
    | If you do not have a key set, run the following command to generate one:
    | php nova key:generate
    |
    */
    'key' => env('APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Error Reporting Levels
    |--------------------------------------------------------------------------
    |
    | You can customize PHP's error reporting level depending on the
    | environment. This allows you to show all errors in development,
    | but limit them in production.
    |
    */
    'error_reporting' => [
        'production'  => E_WARNING,
        'development' => E_ALL
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Versioning
    |--------------------------------------------------------------------------
    |
    | This value allows you to force browsers to load new versions of your
    | assets when they've changed. You can manually bump this version
    | whenever needed to invalidate cached files.
    |
    */
    'asset_version' => 'v1',

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | The default locale determines the language that will be used by
    | the translation system. You can set this to any of the supported locales.
    |
    */
    'locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Here you may define all the locales that your application supports.
    | You can use this to enable language switching for users.
    |
    */
    'locales' => ['en', 'ja'],

];
