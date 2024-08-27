<?php

namespace Nova\Route;

use Nova\Helpers\Modules\Str;
use Nova\Middleware\Middleware;

class Route
{
    /**
     * Default Route Url
     *
     * @var string
     */
    private const DEFAULT_ROUTE = 'novaframe-v1.0.0-default-route';

    /**
     * @var RouteDispatcher
     */
    private RouteDispatcher $dispatcher;

    /**
     * The current URL prefix for the group of routes.
     *
     * @var string
     */
    private static string $prefix = '';

    /**
     * Indicates if the current context is within a group.
     *
     * @var bool
     */
    private static bool $isGroup = false;

    /**
     * Middleware for the current route.
     *
     * @var array
     */
    private array $middlewares = [];

    /**
     * The current route being processed.
     *
     * @var array
     */
    private array $current = ['route' => self::DEFAULT_ROUTE, 'method' => ''];

    public function __construct()
    {
        $this->dispatcher = RouteDispatcher::getInstance();
    }

    /**
     * Creates a new route with the specified attributes.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|array $method The HTTP method(s) supported by the route.
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function create(string $from, string|array|callable $to, string|array $method, string $name = null): Route
    {
        $from = $this->appendPrefix($from);

        $this->dispatcher->add($from, $to, $method, $name, self::$isGroup, self::$prefix);

        if (empty($from) || !str_starts_with($from, '/')) {
            $from = '/' . $from;
        }

        if (strlen($from) !== 1 && str_ends_with($from, DIRECTORY_SEPARATOR)) {
            $from = substr($from, 0, -1);
        }

        $from = trim($from);

        $this->current = [
            'route' => $from,
            'method' => $method
        ];

        if (!empty($this->middlewares)) {
            RouteMiddleware::add(
                method: $method,
                route: $this->current['route'],
                middleware: $this->middlewares,
                name: sprintf("%s~%s", Str::toUpper($method), $this->current['route'])
            );
        }

        return $this;
    }

    /**
     * Creates a new route for GET requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function get(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'get', $name);
    }

    /**
     * Creates a new route for POST requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function post(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'post', $name);
    }

    /**
     * Creates a new route for DELETE requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function delete(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'delete', $name);
    }

    /**
     * Creates a new route for PUT requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function put(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'put', $name);
    }

    /**
     * Creates a new route for PATCH requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function patch(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'patch', $name);
    }

    /**
     * Creates a new route for HEAD requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function head(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'head', $name);
    }

    /**
     * Creates a new route for OPTIONS requests.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function options(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, 'options', $name);
    }

    /**
     * Creates a new route with all http methods and the specified attributes.
     *
     * @param string $from The URL pattern for the route.
     * @param string|array|callable $to The destination for the route (controller action, closure, or view).
     * @param string|null $name The name of the route (optional).
     *
     * @return Route
     */
    public function any(string $from, string|array|callable $to, string $name = null): Route
    {
        return $this->create($from, $to, $this->dispatcher->getAllowedMethods(), $name);
    }

    /**
     * Adds middleware to the current route or group.
     *
     * @param string|array|Middleware $middleware The middleware to add.
     *
     * @return Route
     */
    public function middleware(string|array|Middleware $middleware): Route
    {
        $current = $this->current;

        if ($current['route'] !== self::DEFAULT_ROUTE) {
            RouteMiddleware::add(
                method: $current['method'],
                route: $current['route'],
                middleware: $middleware,
                name: sprintf("%s~%s", Str::toUpper($current['method']), $current['route'])
            );
        }
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * Defines a group of routes with a common URL prefix.
     *
     * @param string $prefix The common URL prefix for the group of routes.
     * @param callable $action The callback function defining the routes within the group.
     *
     * @return Route
     */
    public function group(string $prefix, callable $action): Route
    {
        $previousPrefix  = $this->preparedPrefix();
        $previousIsGroup = self::$isGroup;

        $this->preparedPrefix($prefix, true);
        self::$isGroup = true;

        di()->callback($action);

        $groups = $this->dispatcher->getRouteGroups();

        if (isset($groups[self::$prefix])) {
            foreach ($groups[self::$prefix] as $method => $array) {
                foreach ($array as $route) {
                    if (!empty($this->middlewares)) {
                        RouteMiddleware::add(
                            method: $method,
                            route: $route,
                            middleware: $this->middlewares,
                            name: sprintf("%s~%s", Str::toUpper($method), $route)
                        );
                    }
                }

            }
        }

        self::$prefix  = $previousPrefix;
        self::$isGroup = $previousIsGroup;

        return $this;
    }

    /**
     * Appends the current prefix to the given URL if within a group.
     *
     * @param string $url The URL to append the prefix to.
     *
     * @return string
     */
    private function appendPrefix(string $url): string
    {
        if (self::$isGroup) {
            if (!str_ends_with(self::$prefix, '/') && !str_starts_with($url, '/')) {
                $url = self::$prefix . "/$url";
            } else {
                $url = self::$prefix . $url;
            }
        }

        return $url;
    }

    /**
     * Prepares and updates the current prefix.
     *
     * @param string|null $prefix The new prefix to set.
     * @param bool $update Whether to update the current prefix.
     *
     * @return string
     */
    private function preparedPrefix(string $prefix = null, bool $update = false): string
    {
        if (!empty($prefix) && !empty(trim($prefix))) {
            if ($update) {
                self::$prefix = $prefix;
            } else {
                if (!str_ends_with(self::$prefix, '/') && !str_starts_with($prefix, '/')) {
                    self::$prefix .= "/$prefix";
                } else {
                    self::$prefix .= $prefix;
                }
            }
        }

        return self::$prefix;
    }
}
