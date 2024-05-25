<?php

if (!function_exists('app')) {
    /**
     * Get the singleton instance of the application.
     *
     * This function retrieves the singleton instance of the application, ensuring that only one instance of the application class exists throughout the application's lifecycle.
     *
     * @param string|null $abstract
     * @param mixed       ...$parameters
     *
     * @return mixed
     */
    function app(string $abstract = null, ...$parameters): mixed
    {
        return Nova\Foundation\Application::getInstance($abstract, ...$parameters);
    }
}

if (!function_exists('env')) {
    /**
     * Get an environment variable.
     *
     * @param string $name The key (variable name) to retrieve or set.
     * @param mixed|null $default The default value to return if the value of the specified environment variable is empty
     *
     * @return mixed The value of the specified environment variable if it exists, otherwise default value.
     */
    function env(string $name, mixed $default = null): mixed
    {
        return app('dotenv')->get($name) ?? $default;
    }
}

if (!function_exists('config')) {
    /**
     * Get the value of a configuration item.
     *
     * @param string $key The name of the configuration item.
     * @param mixed $default The default value to return if the value of the specified config value is empty
     * @return mixed The value of the configuration item.
     */
    function config(string $key, mixed $default = null): mixed
    {
        return app('config')->get($key) ?? $default;
    }
}

if (!function_exists('lang')) {
    /**
     * Get a translated message from the language file.
     *
     * @param string $message The message key.
     * @param mixed  ...$placeholder Placeholder values to replace in the message.
     * @return mixed The translated message.
     */
    function lang(string $message, ...$placeholder): mixed
    {
        $language = new Nova\Language\Language();

        return $language->getMessage($message, ...$placeholder);
    }
}
