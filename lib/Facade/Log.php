<?php

namespace NovaFrame\Facade;

/**
 * @method static void setLevel(string $level)
 * @method static void write(string $message, string $level = 'debug', bool $format = true)
 * @method static void debug(string $message, bool $format = true, bool $force = false)
 * @method static void info(string $message, bool $format = true, bool $force = false)
 * @method static void notice(string $message, bool $format = true, bool $force = false)
 * @method static void warning(string $message, bool $format = true, bool $force = false)
 * @method static void error(string $message, bool $format = true, bool $force = false)
 * @method static void critical(string $message, bool $format = true, bool $force = false)
 * @method static void alert(string $message, bool $format = true, bool $force = false)
 * @method static void emergency(string $message, bool $format = true, bool $force = false)
 * @method static mixed read(string $filename, bool $json = false)
 * @method static void clear(string $filename = null)
 * @method static array listFiles()
 * @method static void delete(string $filename)
 * @method static \NovaFrame\Log\Log channel(string $name)
 */
class Log extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Log\Log::class;
    }
}
