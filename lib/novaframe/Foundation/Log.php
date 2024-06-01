<?php

namespace Nova\Foundation;

/**
 * @method static \Nova\Logger\Log write(string $message, string $level = 'DEBUG', bool $format = true)
 * @method static \Nova\Logger\Log fwrite(string $message, string $level = 'DEBUG', bool $format = true)
 * @method static \Nova\Logger\Log custom(string $message, string $level = 'DEBUG', bool $force = false, bool $format = true)
 * @method static \Nova\Logger\Log setLevel(string $level)
 * @method static \Nova\Logger\Log clearLogs()
 * @method static \Nova\Logger\Log read(string $filename)
 */
class Log extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return "\Nova\Logger\Log";
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
