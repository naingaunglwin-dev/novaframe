<?php

namespace NovaFrame\Facade;

/**
 * @method static void sync()
 * @method static void set(array|string $name, mixed $value = null, array $options = [])
 * @method static void send(string $name, mixed $value, array $options = [])
 * @method static mixed get(string $name, $default = null)
 * @method static bool has(string $name)
 * @method static void remove(string $name)
 * @method static void expire(string $name)
 * @method static bool isDirty()
 * @method static bool isExpired(string $name)
 * @method static void clean()
 * @method static mixed pull(string $name, bool $expire = false)
 * @method static bool secure()
 * @method static string domain()
 * @method static string path()
 * @method static bool httponly()
 * @method static string samesite()
 * @method static void save()
 */
class Cookie extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Cookie\Cookie::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}
