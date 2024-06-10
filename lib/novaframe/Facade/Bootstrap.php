<?php

namespace Nova\Facade;

/**
 * @method static \Nova\Service\Bootstrap\Bootstrap before(callable $process)
 * @method static \Nova\Service\Bootstrap\Bootstrap after(callable $process)
 * @method static \Nova\Service\Bootstrap\Bootstrap autoload(array|string $files)
 * @method static \Nova\Service\Bootstrap\Bootstrap web(callable $process)
 * @method static \Nova\Service\Bootstrap\Bootstrap cli(callable $process)
 * @method static \Nova\Service\Bootstrap\Bootstrap getProcess(string $process)
 * @method static \Nova\Service\Bootstrap\Bootstrap run(string $stage)
 */
class Bootstrap extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return service('bootstrap')::class;
    }

    /**
     * Determine if the underlying class should be treated as a singleton.
     *
     * @return bool
     */
    protected static function singleton(): bool
    {
        return true;
    }
}
