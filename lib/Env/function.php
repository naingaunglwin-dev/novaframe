<?php

use NovaFrame\Env\Env;

if (!function_exists('env')) {
    /**
     * Get environment variable or group of variables.
     *
     * @param string $key The environment key or group name.
     * @param mixed $default Default value if key not found.
     * @param bool $group Whether to get group of environment variables.
     * @return mixed The environment value or group array.
     */
    function env(string $key, $default = null, bool $group = false): mixed
    {
        return $group ? Env::group($key, $default) : Env::get($key, $default);
    }
}
