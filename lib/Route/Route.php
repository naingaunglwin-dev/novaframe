<?php

namespace NovaFrame\Route;

use NovaFrame\Kernel;
use NovaFrame\Route\Exceptions\UnsupportedHttpMethod;

class Route implements RouteDefinition
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
    public function create(string $from, callable|array|string $to, array|string $method): Route
    {
        $method = $this->validateHttpMethod($method); // validate http method and cast var type into array & convert to uppercase

        $request = $this->resolveRequestUrl($this->prependPrefix($from));

        $this->saveCurrentRoute($request, $method);

        $this->collection->addRoute($method, $request, [
            'request' => $request,
            'action'  => $to,
            'method'  => $method,
            'name'    => null,
            'middleware' => [],
        ]);

        return $this;
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
    public function get(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'get');
    }

    /**
     * @inheritDoc
     */
    public function post(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'post');
    }

    /**
     * @inheritDoc
     */
    public function head(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'head');
    }

    /**
     * @inheritDoc
     */
    public function put(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'put');
    }

    /**
     * @inheritDoc
     */
    public function patch(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'patch');
    }

    /**
     * @inheritDoc
     */
    public function delete(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'delete');
    }

    /**
     * @inheritDoc
     */
    public function options(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'options');
    }

    /**
     * @inheritDoc
     */
    public function any(string $from, callable|array|string $to): Route
    {
        return $this->create($from, $to, 'any');
    }

    /**
     * @inheritDoc
     */
    public function prefix(string $prefix, callable $routes)
    {
        $oldPrefix = $this->prefix;
        $oldRouteGroupStatus = $this->isGroupRoute;

        $this->resolvePrefix($prefix);
        $this->isGroupRoute = true;

        call_user_func($routes);

        // restore to normal state
        $this->prefix = $oldPrefix;
        $this->isGroupRoute = $oldRouteGroupStatus;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function middleware(array|string $middleware): Route
    {
        $this->collection->addMiddleware($this->currentRoute['method'], $this->currentRoute['request'], $middleware);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function name(string $name): Route
    {
        $this->collection->addRouteName($this->currentRoute['method'], $this->currentRoute['request'], $name);

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

    private function resolvePrefix(string $prefix): void
    {
        if (!empty($prefix)) {
            $this->prefix = rtrim($this->prefix, '/');
            $this->prefix .= '/' . ltrim($prefix, '/'); // for nested prefix route groups
        }
    }

    private function prependPrefix(string $request): string
    {
        if (!$this->isGroupRoute) {
            return $request;
        }

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

    private function validateHttpMethod($method)
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