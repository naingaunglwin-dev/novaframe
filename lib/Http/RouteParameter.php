<?php

namespace NovaFrame\Http;

class RouteParameter
{
    /**
     * Holds the route parameters as key-value pairs.
     *
     * @var array<string, mixed>
     */
    public static array $parameters = [];

    /**
     * Set multiple route parameters.
     *
     * Merges the given parameters into the existing parameter store.
     *
     * @param array<string, mixed> $parameters Associative array of parameter names and their values.
     * @return void
     */
    public static function set(array $parameters): void
    {
        foreach ($parameters as $name => $value) {
            self::$parameters[$name] = $value;
        }
    }

    /**
     * Get a route parameter by name.
     *
     * Returns the value of the specified route parameter if it exists,
     * otherwise returns the provided default value.
     *
     * @param string $name The name of the route parameter to retrieve.
     * @param mixed|null $default The default value to return if parameter not set. Default is null.
     * @return mixed The value of the route parameter or the default value.
     */
    public static function get(string $name, $default = null)
    {
        return self::$parameters[$name] ?? $default;
    }
}
