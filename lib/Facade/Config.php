<?php

namespace NovaFrame\Facade;

/**
 * @method static mixed get(string $key, $default = null)
 */
class Config extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Config\Config::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}