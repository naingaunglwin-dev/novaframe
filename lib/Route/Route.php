<?php

namespace NovaFrame\Route;

use NovaFrame\Kernel;
use NovaFrame\Route\Exceptions\UnsupportedHttpMethod;

class Route implements RouteDefinitionInterface
{
    /**
     * The collection where routes are stored.
     *
     * @var RouteCollection
     */
    private RouteCollection $collection;

    /**
     * List of supported HTTP methods.
     *
     * @var string[]
     */
    private array $httpMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS'];

    private const ROUTE_DEFAULT = 1;
    private const ROUTE_USER_DEFINED = 2;

    /**
     * Whether the current route is part of a group
     *
     * @var bool
     */
    private bool $isGroupRoute = false;

    /**
     * The current prefix applied to routes in a group
     *
     * @var string
     */
    private string $prefix = '';

    /**
     * Stores information about the current route
     *
     * @var array<string, mixed>
     */
    private array $currentRoute = [
        'request' => '',
        'method'  => '',
        'status'  => self::ROUTE_DEFAULT,
    ];

    /**
     * Route middlewares
     *
     * @var array
     */
    private array $middleware = [];

    private string $routeName = '';

    /**
     * Route constructor.
     *
     * Initializes route collection from the application kernel.
     */
    public function __construct()
    {
        $this->collection = Kernel::getInstance()->make('routes');
    }

    /**
     * @inheritDoc
     */
    public function create(string $from, callable|array|string $to, array|string $method): RouteDefinition
    {
        $method = $this->validateHttpMethod($method); // validate http method and cast var type into array & convert to uppercase

        $request = $this->resolveRequestUrl($this->prependPrefix($from));

        $this->saveCurrentRoute($request, $method);

        $this->collection->addRoute($method, $request, [
            'request' => $request,
            'action'  => $to,
            'method'  => $method,
            'name'    => $this->routeName,
            'middleware' => $this->middleware,
        ]);

        if (!$this->isGroupRoute) {
            $this->middleware = []; // Reset middleware if not in a group, to avoid leaking to the next route
        }

        $this->routeName = ''; // Reset route name after registration, so it doesn't carry over

        return new RouteDefinition($this, $this->collection, $request, $method);
    }

    /**
     * Get the currently defined route information.
     *
     * @return array<string, mixed>
     */
    public function getCurrentRoute(): array
    {
        return $this->currentRoute;
    }

    /**
     * @inheritDoc
     */
    public function get(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'get');
    }

    /**
     * @inheritDoc
     */
    public function post(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'post');
    }

    /**
     * @inheritDoc
     */
    public function head(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'head');
    }

    /**
     * @inheritDoc
     */
    public function put(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'put');
    }

    /**
     * @inheritDoc
     */
    public function patch(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'patch');
    }

    /**
     * @inheritDoc
     */
    public function delete(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'delete');
    }

    /**
     * @inheritDoc
     */
    public function options(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'options');
    }

    /**
     * @inheritDoc
     */
    public function any(string $from, callable|array|string $to): RouteDefinition
    {
        return $this->create($from, $to, 'any');
    }

    /**
     * @inheritDoc
     */
    public function prefix(string $prefix): RouteDefinition
    {
        $routesBeforeGroup = $this->collection->getRouteList();

        return new RouteDefinition($this, $this->collection, routesBeforeGroup: $routesBeforeGroup, groupPrefix: $prefix);
    }

    /**
     * @inheritDoc
     */
    public function group(callable $routes): RouteDefinition
    {
        $routesBeforeGroup = $this->collection->getRouteList();
        return (new RouteDefinition($this, $this->collection, routesBeforeGroup: $routesBeforeGroup))->group($routes);
    }

    /**
     * Executes a group of routes within a temporary context that applies a prefix and sets the group route flag.
     *
     * This method is typically used internally by the group/prefix features to apply a common URL prefix
     * and shared state (like middleware) to a set of routes defined in the callback.
     * After execution, the previous routing context is restored.
     *
     * @param string   $prefix   The URL prefix to apply to all routes within the group.
     * @param callable $callback A callback that registers the routes within this group context.
     *
     * @return void
     */
    public function withGroupContext(string $prefix, callable $callback): void
    {
        $oldPrefix = $this->prefix;
        $this->resolvePrefix($prefix);
        $this->isGroupRoute = true;

        $callback($this);

        $this->restore($oldPrefix);
    }

    /**
     * @param string $prefix
     *
     * @return void
     */
    private function restore(string $prefix): void
    {
        $this->prefix = $prefix;
        $this->isGroupRoute = false;
        $this->middleware = [];
    }

    /**
     * @inheritDoc
     */
    public function middleware(array|string $middleware): Route
    {
        $this->middleware = array_merge($this->middleware, is_string($middleware) ? [$middleware] : $middleware);
        $this->middleware = array_unique($this->middleware);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function name(string $name): Route
    {
        $this->routeName = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fallback(callable $action): void
    {
        $this->collection->setFallbackAction($action);
    }

    private function resolveRequestUrl(string $url): string
    {
        $url = trim($this->normalizeRequestUrl($url));

        if (empty($url) || !str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        if (strlen($url) > 1) {
            $url = rtrim($url, '\\/');
        }

        return $url;
    }

    private function normalizeRequestUrl(string $url): string
    {
        return str_replace(['\\', '//', '\\\\'], '/', $url);
    }

    protected function resolvePrefix(string $prefix): void
    {
        if (!empty($prefix)) {
            $this->prefix = rtrim($this->prefix, '/');
            $this->prefix .= '/' . ltrim($prefix, '/'); // for nested prefix route groups
        }
    }

    private function prependPrefix(string $request): string
    {
        return $this->prefix . '/' . ltrim($request, '/');
    }

    private function saveCurrentRoute(string $request, array $method): void
    {
        $this->currentRoute = [
            'request' => $request,
            'method'  => $method,
            'status'  => self::ROUTE_USER_DEFINED,
        ];
    }

    /**
     * @param string|array $method
     * @return string[]
     */
    private function validateHttpMethod(string|array $method): array
    {
        if ($method === 'any') {
            return $this->httpMethods;
        }

        if (is_string($method)) {
            $method = [$method];
        }

        foreach ($method as $key => $value) {
            $value = strtoupper($value);

            if (!in_array($value, $this->httpMethods)) {
                throw new UnsupportedHttpMethod($value);
            }

            $method[$key] = $value;
        }

        return $method;
    }
}