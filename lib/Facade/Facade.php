<?php

namespace NovaFrame\Facade;

use NovaFrame\Container\Container;
use NovaFrame\Facade\Exceptions\EmptyAccessor;

class Facade
{
    /**
     * Get the accessor name for the underlying class or service.
     *
     * Child classes must override this to specify the key or class name
     * that will be resolved from the container.
     *
     * @return string The service name or class string to resolve
     */
    protected static function accessor(): string
    {
        return '';
    }

    /**
     * Whether the binding should be a singleton in the container.
     *
     * Child classes can override this to return true to bind the resolved
     * class as a singleton.
     *
     * @return bool True if singleton, false otherwise
     */
    protected static function singleton(): bool
    {
        return false;
    }

    /**
     * Resolve the underlying instance from the container.
     *
     * @throws EmptyAccessor if accessor() returns empty string or '0'
     * @return mixed The resolved instance
     */
    private static function resolve(): mixed
    {
        $concrete = static::accessor();

        if ($concrete === '' || $concrete === '0') {
            throw new EmptyAccessor();
        }

        $container = Container::getInstance();

        $abstract = "Facade\\{$concrete}";

        if (!$container->has($abstract)) {
            $method = static::singleton() ? 'singleton' : 'add';
            $container->{$method}($abstract, $concrete);
        }

        return $container->make($abstract);
    }

    /**
     * Handle dynamic, static method calls into the facade.
     *
     * Forwards the call to the resolved underlying instance.
     *
     * @param string $method The method name being called
     * @param array  $args   The method arguments
     * @return mixed The result of the underlying method call
     */
    public static function __callStatic($method, $args)
    {
        return static::resolve()->{$method}(...$args);
    }
}
