<?php

if (!function_exists('config')) {
    /**
     * This is a global helper function that proxies to the Config facade.
     *
     * Example:
     * ```php
     * config('app.name');
     * config('database.connections.mysql.host', '127.0.0.1');
     * ```
     *
     * @param string $key The configuration key (e.g., 'app.name').
     * @param mixed|null $default The default value to return if the key is not found.
     * @return mixed The configuration value or the default.
     */
    function config(string $key, $default = null): mixed
    {
        return \NovaFrame\Facade\Config::get($key, $default);
    }
}
