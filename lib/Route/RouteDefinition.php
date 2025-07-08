<?php

namespace NovaFrame\Route;

use NovaFrame\Middleware\Middleware;


class RouteDefinition
{
    /**
     * Routes added during group context, grouped by HTTP method.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $groupRoutes;

    /**
     * Stores the keys (method and request path) for a single route.
     *
     * @var array{request: string, methods: string[]}|array
     */
    private array $routeKeys = [];

    /**
     * RouteDefinition constructor.
     *
     * @param Route $router The main Route instance used for defining routes.
     * @param RouteCollection $collection The route collection where routes are stored.
     * @param string|null $request The request path (used for single routes).
     * @param string[]|null $methods The HTTP methods (used for single routes).
     * @param array $routesBeforeGroup Route list before the group was executed.
     * @param string $groupPrefix The prefix for the group (e.g., "admin", "api/v1").
     */
    public function __construct(
        private Route $router,
        private RouteCollection $collection,
        private ?string $request = null,
        private ?array $methods = null,
        array $routesBeforeGroup = [],
        private readonly string $groupPrefix = ''
    )
    {
        $this->groupRoutes = $this->detectNewRoutes(
            $routesBeforeGroup,
            $this->collection->getRouteList()
        );

        if ($this->request !== null && $this->methods !== null) {
            $this->routeKeys = ['request' => $this->request, 'methods' => $this->methods];
        }
    }

    /**
     * Applies middleware to the current route or all routes defined in the group.
     *
     * @param string|array<string>|Middleware $middleware
     * @return $this
     */
    public function middleware(string|array|Middleware $middleware): RouteDefinition
    {
        if (!empty($this->groupRoutes)) {
            foreach ($this->groupRoutes as $method => $routes) {
                foreach ($routes as $route => $_) {
                    $this->collection->addMiddleware([$method], $route, $middleware);
                }
            }
        } elseif (!empty($this->routeKeys)) {
            $this->collection->addMiddleware($this->routeKeys['methods'], $this->routeKeys['request'], $middleware);
        }

        return $this;
    }

    /**
     * Assigns a name to the current route for use in route generation.
     *
     * @param string $name The name of the route.
     * @return $this
     */
    public function name(string $name): RouteDefinition
    {
        if (!empty($this->routeKeys)) {
            $this->collection->addRouteName($this->routeKeys['methods'], $this->routeKeys['request'], $name);
        }

        return $this;
    }

    /**
     * Defines a nested route group within the current group context.
     *
     * @param callable $routes The route definitions within the subgroup.
     * @return RouteDefinition A new scoped instance for the nested group.
     */
    public function group(callable $routes): RouteDefinition
    {
        $before = $this->collection->getRouteList();

        $this->router->withGroupContext($this->groupPrefix, $routes);

        return new self($this->router, $this->collection, routesBeforeGroup: $before, groupPrefix: $this->groupPrefix);
    }

    /**
     * Compares two route lists and returns only the newly added routes.
     *
     * @param array<string, array<string, mixed>> $before Routes before group.
     * @param array<string, array<string, mixed>> $after Routes after group.
     * @return array<string, array<string, mixed>> Newly added routes.
     */
    private function detectNewRoutes(array $before, array $after): array
    {
        $diff = [];

        foreach ($after as $method => $routes) {
            foreach ($routes as $route => $handler) {
                if (!isset($before[$method][$route])) {
                    $diff[$method][$route] = $handler;
                }
            }
        }

        return $diff;
    }
}
