<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Log\Log setLevel(string $level)
 * @method static \NovaFrame\Log\Log write(string $message, string $level = 'debug', bool $format = true)
 * @method static \NovaFrame\Log\Log debug(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log info(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log notice(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log warning(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log error(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log critical(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log alert(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log emergency(string $message, bool $format = true, bool $force = false)
 * @method static \NovaFrame\Log\Log read(string $filename, bool $json = false)
 * @method static \NovaFrame\Log\Log clear(string $filename = null)
 * @method static \NovaFrame\Log\Log listFiles()
 * @method static \NovaFrame\Log\Log delete(string $filename)
 * @method static \NovaFrame\Log\Log channel(string $name)
 */
class Log extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Log\Log::class;
    }
}
