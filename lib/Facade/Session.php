<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Session\Session start()
 * @method static \NovaFrame\Session\Session get(string $key, $default = null)
 * @method static \NovaFrame\Session\Session set(string $key, $value)
 * @method static \NovaFrame\Session\Session has(string $key)
 * @method static \NovaFrame\Session\Session remove(string $key)
 * @method static \NovaFrame\Session\Session all()
 * @method static \NovaFrame\Session\Session clear()
 * @method static \NovaFrame\Session\Session forget(string $key)
 * @method static \NovaFrame\Session\Session flash(string $key, $value)
 * @method static \NovaFrame\Session\Session getFlash(string $key, mixed $default = null)
 * @method static \NovaFrame\Session\Session hasFlash(string $key)
 * @method static \NovaFrame\Session\Session keepFlash(string $key)
 * @method static \NovaFrame\Session\Session keepAllFlash()
 * @method static \NovaFrame\Session\Session clearFlash()
 * @method static \NovaFrame\Session\Session regenerateId(bool $deleteOldSession = false)
 * @method static \NovaFrame\Session\Session getCsrfToken()
 * @method static \NovaFrame\Session\Session validateCsrfToken(string $token)
 * @method static \NovaFrame\Session\Session getId()
 * @method static \NovaFrame\Session\Session getName()
 * @method static \NovaFrame\Session\Session save()
 * @method static \NovaFrame\Session\Session destroy()
 * @method static \NovaFrame\Session\Session isStarted()
 * @method static \NovaFrame\Session\Session restart()
 */
class Session extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Session\Session::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}
