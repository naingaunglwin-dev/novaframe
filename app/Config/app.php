<?php

/*
 |--------------------------------------------------------------------------------
 | Configuration settings for the application.
 |--------------------------------------------------------------------------------
 |
 | This array contains various configuration settings used by the application.
 | Each setting has a key-value pair defining its purpose and default value,
 | which can be overridden via environment variables.
 |
 | @return array An array of configuration settings.
 |
 */
return [

    /*
     |--------------------------------
     | Application Name
     |--------------------------------
     */
    'name' => env('APP_NAME', 'NOVAFRAME'),

    /*
     |----------------------------------------------------------------------------------------------------------------------------------
     | Application Base URL
     |----------------------------------------------------------------------------------------------------------------------------------
     |
     | The base URL of the application.
     | This URL is used as the base for generating absolute URLs within the application.
     | It is typically set to the root URL of the application, including the protocol (e.g., http://localhost/novaframe/public).
     |
     */
    'base_url' => env('APP_BASE_URL', 'http://localhost/novaframe/public'),

    /*
     |------------------------------------------------------------------------------------------------
     | Application Base Path (root directory of project)
     |------------------------------------------------------------------------------------------------
     |
     | The base path of the application.
     | This path represents the root directory of the project on the server's filesystem.
     | It is used as a reference point for accessing files and directories within the project.
     |
     */
    'base_path' => env('APP_BASE_PATH',
        str_ends_with(ROOT_PATH, '/')
            ? ROOT_PATH
            : ROOT_PATH . "/"
    ),

    /*
     |---------------------------------------------------------------------------------------------
     | Application's Environment (production, development, ...)
     |---------------------------------------------------------------------------------------------
     |
     | The environment mode of the application.
     | This setting determines the current environment in which the application is running,
     | such as 'production', 'development', etc.
     |
     */
    'environment' => env('APP_ENVIRONMENT', 'production'),

    /*
     |-------------------------------------------------------------------------------------------------------
     | Application Time Zone
     |-------------------------------------------------------------------------------------------------------
     |
     | The default time zone used by the application.
     | This setting defines the default time zone for date and time operations within the application.
     | It should be set to a valid time zone identifier (e.g., 'UTC', 'America/New_York').
     |
     */
    'timezone' => env('APP_TIME_ZONE', 'UTC'),

    /*
     |---------------------------------------------------------------------------------
     | Application's Secret Key
     |---------------------------------------------------------------------------------
     |
     | The secret key used for encryption and hashing operations.
     | This key is used for generating secure tokens, encrypting sensitive data,
     | and other cryptographic operations within the application.
     |
     */
    'key' => env('APP_KEY'),

    /*
     |----------------------------------------------------------------------------------------
     | Error Reporting Level
     |----------------------------------------------------------------------------------------
     |
     | Define the error reporting level for different environments.
     | This setting controls which PHP errors and warnings are displayed or logged.
     |
     */
    'error_reporting_level' => [
        'production'  => E_WARNING,
        'development' => E_ALL
    ],

    /*
     |-----------------------------------------------------------------------------------------------------
     | Asset Version
     |-----------------------------------------------------------------------------------------------------
     |
     | Your application's asset version which will used in framework's `css()`, `js()` methods.
     | This allows you to avoid forcing a reload after your CSS or JS changes; just update this version.
     |
     */
    'asset_version' => 'v1',

    /*
     |------------------------------------------------------------
     | Locale
     |------------------------------------------------------------
     |
     | Your application's locale, used in locale process,
     | such as displaying language changes based on the locale.
     |
     */
    'locale' => env('APP_LOCALE', 'en'),

    /*
     |---------------------------------------------------------------------------------------------------------
     | Supported Locales List
     |---------------------------------------------------------------------------------------------------------
     |
     | List of your application's supported locale,
     | If the system detects a locale that is not included in this list,
     | you can choose to show a framework default exception or define a custom callback function.
     | You can also set a default fallback language if the set locale is not found.
     |
     */
    'locales' => [
        'onNotFound' => [
            'default'   => 'en',
            'exception' => true,
            'callback'  => function () {},
        ],

        'list' => [
            'en',
        ]
    ],

    /*
     |------------------------------------------
     | File Paths
     |------------------------------------------
     |
     | Your application's file paths
     |
     */
    'paths' => [
        'controller' => APP_PATH . 'HTTP/Controllers',
        'model'      => APP_PATH . 'Models',
        'middleware' => APP_PATH . 'HTTP/Middlewares',
        'command'    => APP_PATH . 'bin',
        'migration'  => APP_PATH . 'Migration/Migrations',
        'seed'       => APP_PATH . 'Migration/Seeds',
        'route'      => APP_PATH . 'Config',
        'language'   => APP_PATH . 'Languages',
    ],

    /*
     |------------------------------------------
     | Namespaces
     |------------------------------------------
     |
     | Your application's namespaces
     |
     */
    'namespace' => [
        'controller' => '\\App\\HTTP\\Controllers',
        'model'      => '\\App\\Models',
        'middleware' => '\\App\\HTTP\\Middlewares',
        'command'    => '\\App\\bin',
        'migration'  => '\\App\\Migration\\Migrations',
        'seed'       => '\\App\\Migration\\Seeds'
    ],

];
