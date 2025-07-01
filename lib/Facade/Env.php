<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Env\Env get(?string $key = null, mixed $default = null)
 * @method static \NovaFrame\Env\Env group(?string $key = null, mixed $default = null)
 * @method static \NovaFrame\Env\Env has(string $key)
 * @method static \NovaFrame\Env\Env load()
 * @method static \NovaFrame\Env\Env reload()
 * @method static \NovaFrame\Env\Env getDefaultEnvFiles()
 */
class Env extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Env\Env::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}
