<?php

namespace Nova\Facade;

/**
 * @method static \Nova\Session\Session set(string $key, mixed $value, bool $overwrite = false)
 * @method static \Nova\Session\Session get(string $key, mixed $default = null)
 * @method static \Nova\Session\Session getAll()
 * @method static \Nova\Session\Session destroy(string $key)
 * @method static \Nova\Session\Session destroyAll()
 * @method static \Nova\Session\Session isSecure()
 * @method static \Nova\Session\Session setFlashMessage(string $key, mixed $value)
 * @method static \Nova\Session\Session getFlashMessage(string $key)
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
        return "\Nova\Session\Session";
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