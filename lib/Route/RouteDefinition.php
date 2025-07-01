<?php

namespace NovaFrame\Route;

/**
 * Interface RouteDefinition
 *
 * Defines the contract for a route registration system,
 * including HTTP methods, route grouping, middleware assignment,
 * naming, and fallback handling.
 */
interface RouteDefinition
{
    /**
     * Create a route with specified HTTP method(s) and action.
     *
     * @param string $from The URI pattern of the route.
     * @param string|array|callable $to The action to be executed.
     * @param string|array $method HTTP method(s) allowed for this route.
     * @return mixed
     */
    public function create(string $from, string|array|callable $to, string|array $method);

    /**
     * Register a route that responds to GET requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function get(string $from, string|array|callable $to);

    /**
     * Register a route that responds to POST requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function post(string $from, string|array|callable $to);

    /**
     * Register a route that responds to HEAD requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function head(string $from, string|array|callable $to);

    /**
     * Register a route that responds to PUT requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function put(string $from, string|array|callable $to);

    /**
     * Register a route that responds to PATCH requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function patch(string $from, string|array|callable $to);

    /**
     * Register a route that responds to DELETE requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function delete(string $from, string|array|callable $to);

    /**
     * Register a route that responds to OPTIONS requests.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function options(string $from, string|array|callable $to);

    /**
     * Register a route that responds to any HTTP method.
     *
     * @param string $from The URI pattern.
     * @param string|array|callable $to The route action.
     * @return mixed
     */
    public function any(string $from, string|array|callable $to);

    /**
     * Define a group of routes sharing a common URI prefix.
     *
     * @param string $prefix The URI prefix.
     * @param callable $routes A callback defining routes under this prefix.
     * @return mixed
     */
    public function prefix(string $prefix, callable $routes);

    /**
     * Assign middleware(s) to the current route or group.
     *
     * @param string|array $middleware Middleware class name(s) or identifiers.
     * @return mixed
     */
    public function middleware(string|array $middleware);

    /**
     * Assign a name to the current route.
     *
     * @param string $name The route name.
     * @return mixed
     */
    public function name(string $name);

    /**
     * Define a fallback action when no route matches.
     *
     * @param callable $action The fallback callable.
     * @return mixed
     */
    public function fallback(callable $action);
}
