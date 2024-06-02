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
        return \Nova\Facade\Language::getMessage($message, ...$placeholder);
    }
}

if (!function_exists('session')) {
    /**
     * Get or set a session value.
     *
     * If only the $key parameter is provided, retrieves the value associated with
     * that key from the session. If both $key and $value parameters are provided,
     * sets the value associated with the key in the session.
     * If no parameters are provided, returns a new Session instance.
     *
     * Example usage:
     * - session('user_id') // Get the value associated with the 'user_id' key
     * - session('user_name', 'John Doe') // Set the 'user_name' key to 'John Doe'
     * - session('user_name', 'David', true) // Overwrite the value of 'user_name' with 'David' if it exists
     * - $session = session(); // Retrieve a new Session instance
     *
     * @param string|null $key       The key of the session value to get or set.
     * @param mixed|null  $value     (optional) The value to set in the session.
     * @param bool        $overwrite Whether to overwrite the value if it exists
     *
     * @return mixed If only $key is provided, returns the value associated with that key.
     *               If both $key and $value are provided, returns void.
     *               If no parameters are provided, returns a new instance of Session.
     */
    function session(string $key = null, mixed $value = null, bool $overwrite = false)
    {
        if ($key !== null) {
            if ($value !== null) {
                \Nova\Facade\Session::set($key, $value, $overwrite);
            }

            return \Nova\Facade\Session::get($key);
        }

        return new \Nova\Service\Session\Session();
    }
}

if (!function_exists('service')) {
    /**
     * Get a service instance.
     *
     * Example usage:
     * - service('config') // Get the Config service
     * - service('session', $param1, $param2) // Get the Session service with parameters
     *
     * @param string $service    The name of the service to retrieve.
     * @param mixed  ...$parameters Optional parameters to pass to the service constructor.
     *
     * @return mixed The service instance.
     */
    function service(string $service, mixed ...$parameters): mixed
    {
        return \Nova\Facade\Service::get($service, ...$parameters);
    }
}
