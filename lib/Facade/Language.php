<?php

namespace NovaFrame\Facade;

use NovaFrame\Facade\Facade;

/**
 * @method static \NovaFrame\Language\Language get(string $key, array $params = [])
 */
class Language extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Language\Language::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}
