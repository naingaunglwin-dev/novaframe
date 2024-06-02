<?php

namespace Nova\Facade;

/**
 * @method static \Nova\Service\Service get(string $service, mixed ...$parameters)
 */
class Service extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return \Nova\Service\Service::class;
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