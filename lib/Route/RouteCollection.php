<?php

namespace NovaFrame\Route;

use NovaFrame\Route\Exceptions\RouteNotFound;

class RouteCollection
{
    /**
     * Stores all routes organized by HTTP method and URI.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Maps route names to their corresponding URI.
     *
     * @var array
     */
    private array $routeNames = [];

    /**
     * Add a route to the collection.
     *
     * @param array $methods HTTP methods supported by the route.
     * @param string $route The route URI pattern.
     * @param array $items The route data (action, middleware, name, etc).
     *
     * @return void
     */
    public function addRoute(array $methods, string $route, array $items): void
    {
        foreach ($methods as $method) {
            $this->routes['list'][$method][$route] = $items;
        }
    }

    /**
     * Assign middleware(s) to a route.
     *
     * @param array $methods HTTP methods of the route.
     * @param string $route The route URI pattern.
     * @param array|string $middlewares Middleware(s) to assign.
     * @throws RouteNotFound If the route does not exist for the given method.
     *
     * @return void
     */
    public function addMiddleware(array $methods, string $route, $middlewares): void
    {
        foreach ($methods as $method) {
            if (!isset($this->routes['list'][$method][$route])) {
                throw new RouteNotFound($route, $method);
            }

            if (is_string($middlewares)) {
                $middlewares = [$middlewares];
            }

            $this->routes['list'][$method][$route]['middleware'] = $middlewares;
        }
    }

    /**
     * Assign a name to a route.
     *
     * @param array $methods HTTP methods of the route.
     * @param string $route The route URI pattern.
     * @param string $name The name to assign to the route.
     * @throws RouteNotFound If the route does not exist for the given method.
     *
     * @return void
     */
    public function addRouteName(array $methods, string $route, string $name): void
    {
        foreach ($methods as $method) {
            if (!isset($this->routes['list'][$method][$route])) {
                throw new RouteNotFound($route, $method);
            }

            $this->routes['list'][$method][$route]['name'] = $name;
            $this->routeNames[$name] = $this->splitRoutesIntoSegments($route);
        }
    }

    /**
     * Set the fallback action to be used when no route matches.
     *
     * @param callable $action The fallback callable action.
     *
     * @return void
     */
    public function setFallbackAction(callable $action): void
    {
        $this->routes['fallback'] = $action;
    }

    /**
     * Get all registered routes.
     *
     * @return array The list of routes grouped by HTTP method.
     */
    public function getRouteList(): array
    {
        return $this->routes['list'] ?? [];
    }

    /**
     * Retrieve the URI of a route by its name.
     *
     * @param string $name The route name.
     *
     * @return array The route URI segments.
     */
    public function getRouteByName(string $name): array
    {
        return $this->routeNames[$name];
    }

    /**
     * Save the route and name collections from a cached route file.
     *
     * @param array $cached Cached route data containing 'routes' and 'names'.
     * @return void
     */
    public function saveCached(array $cached): void
    {
        $this->routes = $cached['routes'] ?? [];
        $this->routeNames = $cached['names'] ?? [];
    }

    /**
     * Return all route collections
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'routes' => $this->routes,
            'names' => $this->routeNames
        ];
    }

    /**
     * split route url into segments
     *
     * @param string $route
     * @return string[]
     */
    private function splitRoutesIntoSegments(string $route): array
    {
        $split = explode('/', $route);

        if (empty($split)) {
            return ['/' => '/']; // assume route is base route
        }

        $segments = [];

        foreach ($split as $segment) {
            $segments[] = $segment;
        }

        return $segments;
    }
}
