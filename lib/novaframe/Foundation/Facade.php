<?php

namespace Nova\Foundation;

use BadMethodCallException;
use Nova\Container\Container;

class Facade
{
    /**
     * Holds the resolved instances of facades.
     *
     * @var array
     */
    private static array $aliases = [];

    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return '';
    }

    /**
     * Determine if the underlying class should be treated as a singleton.
     *
     * @return bool
     */
    protected static function singleton(): bool
    {
        return false;
    }

    /**
     * Resolve the underlying class from the container.
     *
     * @throws BadMethodCallException
     */
    private static function resolve(): void
    {
        $class = static::defineClass();

        if (empty($class)) {
            throw new BadMethodCallException("Class is not defined yet");
        }

        if (!class_exists($class)) {
            throw new BadMethodCallException("Class {$class} is not found");
        }

        $singleton = static::singleton();

        $container = new Container();

        if ($singleton) {
            $container->singleton("Facade:[$class]", $class);
        } else {
            $container->add("Facade:[$class]", $class);
        }

        self::$aliases[$class] = $container->make("Facade:[$class]");
    }

    /**
     * Handle dynamic method calls to the facade.
     *
     * @param string $method
     * @param array  $args
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $method, array $args)
    {
        self::resolve();

        if (!isset(self::$aliases[static::defineClass()])) {
            throw new BadMethodCallException("Class is not defined yet");
        }

        return self::$aliases[static::defineClass()]->{$method}(...$args);
    }
}
