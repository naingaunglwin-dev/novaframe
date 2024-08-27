<?php

namespace Nova\Route;

use Nova\Helpers\Modules\Str;
use Nova\HTTP\IncomingRequest;
use Nova\Middleware\Middleware;
use Nova\Middleware\MiddlewareHandler;

class RouteMiddleware
{
    /**
     * The list of route middlewares
     *
     * @var array
     */
    private static array $middlewares = [];

    /**
     * Add middleware to route
     *
     * @param string|array<string> $method HTTP methods
     * @param string $route Route url
     * @param string|array|Middleware $middleware Middleware to apply route
     * @param string $name Middleware name to store with in array
     *
     * @return void
     */
    public static function add(string|array $method, string $route, string|array|Middleware $middleware, string $name): void
    {
        if (is_string($method)) {
            static::$middlewares[$name][Str::toUpper($method)][$route] = $middleware;
        } else {
            foreach ($method as $m) {
                static::add($m, $route, $middleware, $name);
            }
        }
    }

    /**
     * Get defined route middleware
     *
     * @param string $name Middleware name
     * @param mixed $default Default value to return when middleware with given name is not found; default: null
     *
     * @return mixed
     */
    public static function get(string $name, $default = null): mixed
    {
        return static::$middlewares[$name] ?? $default;
    }

    /**
     * Handle the middleware process
     *
     * @param string $name Middleware name
     * @param IncomingRequest $request
     *
     * @return IncomingRequest
     */
    public static function handle(string $name, IncomingRequest $request): IncomingRequest
    {
        $handler = new MiddlewareHandler();

        if (Str::toLower($name) === 'default') {
            $request = $handler->handleDefault($request);
        } else {
            if (!empty($middlewares = static::get($name))) {
                $request = $handler->handle($request, $middlewares);
            }
        }

        return $request;
    }
}
