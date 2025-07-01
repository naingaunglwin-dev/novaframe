<?php

use NovaFrame\Kernel;

if (!function_exists('app')) {
    /**
     * Get the application instance or resolve a class from the container.
     *
     * @param string|null $abstract The class or interface name to resolve.
     * @param array $parameters Optional parameters for constructor or method injection.
     * @return mixed The app instance or the resolved object.
     */
    function app(?string $abstract = null, array $parameters = [])
    {
        $app = Kernel::getInstance();

        if (empty($abstract)) {
            return $app;
        }

        return $app->make($abstract, $parameters);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token from the session.
     *
     * @return string
     */
    function csrf_token()
    {
        return \NovaFrame\Facade\Session::getCsrfToken();
    }
}

if (!function_exists('helper')) {
    /**
     * Load helper files by name(s).
     *
     * @param string ...$name One or more helper file names (without extension).
     * @return void
     */
    function helper(string ...$name): void
    {
        \NovaFrame\Helpers\HelperLoader::getInstance()->load(...$name);
    }
}
