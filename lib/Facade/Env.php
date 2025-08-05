<?php

namespace NovaFrame\Facade;

/**
 * @method static mixed get(?string $key = null, mixed $default = null)
 * @method static mixed group(?string $key = null, mixed $default = null)
 * @method static bool has(string $key)
 * @method static \NovaFrame\Env\Env load()
 * @method static \NovaFrame\Env\Env reload()
 * @method static array getDefaultEnvFiles()
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
