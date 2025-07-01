<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Global Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will run during every request to your application.
    | You may use this array to register middleware classes that should be
    | executed in a global context, regardless of the route or module.
    |
    */
    'global' => [
        \App\Http\Middlewares\TrackPreviousUrl::class,
        \App\Http\Middlewares\CsrfToken::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | Middleware aliases allow you to assign short names to middleware classes.
    | These aliases can then be used when defining route-specific middleware
    | instead of referencing the full class name every time.
    |
    */
    'alias' => [
        // e.g. 'csrf' => \App\Http\Middlewares\CsrfToken::class,
    ],
];
