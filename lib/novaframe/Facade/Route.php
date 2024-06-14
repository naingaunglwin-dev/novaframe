<?php

namespace Nova\Facade;

use Nova\Middleware\Middleware;

/**
 * @method static \Nova\Route\Route create(string $from, string|array|callable $to, string|array $method, string $name = null)
 * @method static \Nova\Route\Route get(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route post(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route delete(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route put(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route patch(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route head(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route options(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route any(string $from, string|array|callable $to, string $name = null)
 * @method static \Nova\Route\Route middleware(string|array|Middleware $middleware)
 * @method static \Nova\Route\Route group(string $prefix, callable $action)
 */
class Route extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return \Nova\Route\Route::class;
    }

    /**
     * Determine if the underlying class should be treated as a singleton.
     *
     * @return bool
     */
    protected static function singleton(): bool
    {
        return true;
    }
}
