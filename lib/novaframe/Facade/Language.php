<?php

namespace Nova\Facade;

/**
 * @method static \Nova\Service\Language\Language getMessage(string $key, string ...$placeholder)
 */
class Language extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return service('language')::class;
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
}
