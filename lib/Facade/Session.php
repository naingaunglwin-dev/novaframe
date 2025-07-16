<?php

namespace NovaFrame\Facade;

/**
 * @method static bool start()
 * @method static mixed get(string $key, $default = null)
 * @method static bool set(string $key, $value)
 * @method static bool has(string $key)
 * @method static void remove(string $key)
 * @method static array all()
 * @method static void clear()
 * @method static void forget(string $key)
 * @method static void flash(string $key, $value)
 * @method static mixed getFlash(string $key, mixed $default = null)
 * @method static bool hasFlash(string $key)
 * @method static void regenerateId(bool $deleteOldSession = false)
 * @method static string getCsrfToken()
 * @method static bool validateCsrfToken(string $token)
 * @method static null|string id()
 * @method static string getName()
 * @method static void save()
 * @method static bool destroy()
 * @method static void restart()
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
