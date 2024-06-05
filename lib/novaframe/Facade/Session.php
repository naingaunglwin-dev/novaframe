<?php

namespace Nova\Facade;

/**
 * @method static \Nova\Service\Session\Session set(string $key, mixed $value, bool $overwrite = false)
 * @method static \Nova\Service\Session\Session get(string $key, mixed $default = null)
 * @method static \Nova\Service\Session\Session getAll()
 * @method static \Nova\Service\Session\Session destroy(string $key)
 * @method static \Nova\Service\Session\Session destroyAll()
 * @method static \Nova\Service\Session\Session isSecure()
 * @method static \Nova\Service\Session\Session setFlashMessage(string $key, mixed $value)
 * @method static \Nova\Service\Session\Session getFlashMessage(string $key)
 */
class Session extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return service('session')::class;
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