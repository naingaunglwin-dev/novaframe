<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Route\Route create(string $from, callable|array|string $to, array|string $method)
 * @method static \NovaFrame\Route\Route get(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route post(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route head(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route put(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route patch(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route delete(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route options(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route any(string $from, callable|array|string $to)
 * @method static \NovaFrame\Route\Route prefix(string $prefix, callable $routes)
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
