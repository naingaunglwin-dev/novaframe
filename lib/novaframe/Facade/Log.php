<?php

namespace Nova\Facade;

/**
 * @method static \Nova\Service\Logger\Log write(string $message, string $level = 'DEBUG', bool $format = true)
 * @method static \Nova\Service\Logger\Log fwrite(string $message, string $level = 'DEBUG', bool $format = true)
 * @method static \Nova\Service\Logger\Log custom(string $message, string $level = 'DEBUG', bool $force = false, bool $format = true)
 * @method static \Nova\Service\Logger\Log setLevel(string $level)
 * @method static \Nova\Service\Logger\Log clearLogs()
 * @method static \Nova\Service\Logger\Log read(string $filename)
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
        return \Nova\Service\Logger\Log::class;
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
