<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Route\RouteDefinition create(string $from, callable|array|string $to, array|string $method)
 * @method static \NovaFrame\Route\RouteDefinition get(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition post(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition head(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition put(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition patch(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition delete(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition options(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition any(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\RouteDefinition prefix(string $prefix)
 * @method static \NovaFrame\Route\RouteDefinition group(callable $routes)
 * @method static \NovaFrame\Route\Route middleware(array|string $middlewares)
 * @method static \NovaFrame\Route\Route name(string $name)
 * @method static \NovaFrame\Route\Route fallback(callable $action)
 */
class Route extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Route\Route::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}
