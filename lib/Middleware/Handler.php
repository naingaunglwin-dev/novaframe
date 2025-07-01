<?php

namespace NovaFrame\Middleware;

use NovaFrame\Http\RedirectResponse;
use NovaFrame\Http\Request;
use NovaFrame\Http\Response;
use NovaFrame\Middleware\Exceptions\InvalidMiddlewareStructure;
use NovaFrame\Middleware\Exceptions\MiddlewareException;
use NovaFrame\Middleware\Exceptions\MiddlewareMustReturnResponse;
use NovaFrame\Pipeline\Pipeline;

class Handler
{
    /**
     * Flag to indicate if middleware configs are loaded.
     *
     * @var bool
     */
    private bool $loaded = false;

    /**
     * List of global middleware class names.
     *
     * @var array<string>
     */
    private array $global = [];

    /**
     * Middleware aliases map alias => class name.
     *
     * @var array<string, string>
     */
    private array $alias = [];

    /**
     * Handler constructor.
     *
     * Loads middlewares
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     * Loads middlewares from the config file.
     *
     * Avoids re-loading if already loaded in production.
     *
     * @return void
     */
    private function load()
    {
        if (config('app.env', 'production') === 'production') {
            if ($this->loaded) {
                return;
            } else {
                $this->loaded = true;
            }
        }

        $middlewares = config('middleware', []);

        $this->global = $middlewares['global'] ?? [];
        $this->alias  = $middlewares['alias'] ?? [];
    }

    /**
     * Process global middleware stack.
     *
     * @param Request $request
     * @param Response $response
     * @param callable|null $kernel Optional final callable after middleware pipeline.
     * @return Response
     */
    public function handleGlobals(Request $request, Response $response, ?callable $kernel = null): Response
    {
        return $this->doHandle($request, $response, $this->global, $kernel);
    }

    /**
     * Resolve middleware aliases to their full class names.
     *
     * @param Middleware|array|string $middlewares
     * @return array<string>
     */
    private function resolve(Middleware|array|string $middlewares)
    {
        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        foreach ($middlewares as $index => $middleware) {
            if (isset($this->alias[$middleware])) {
                $middlewares[$index] = $this->alias[$middleware];
            }
        }

        return $middlewares;
    }

    /**
     * Handle a given set of middleware.
     *
     * @param Request $request
     * @param Response $response
     * @param Middleware|array|string $middlewares Middleware(s) to run.
     * @param callable|null $kernel Optional final callable after middleware pipeline.
     * @return Response
     */
    public function handle(Request $request, Response $response, Middleware|array|string $middlewares, ?callable $kernel = null): Response
    {
        $middlewares = $this->resolve($middlewares);

        return $this->doHandle($request, $response, $middlewares, $kernel);
    }

    /**
     * Adapt a middleware class to a callable function compatible with the pipeline.
     *
     * @param string $middleware Fully qualified middleware class name.
     * @return callable
     *
     * @throws InvalidMiddlewareStructure If the middleware class doesn't have a handle method.
     */
    private function adaptMiddleware(string $middleware): callable
    {
        return function (Request $request, callable $next) use ($middleware) {
            $instance = new $middleware();

            if (!method_exists($instance, 'handle')) {
                throw new InvalidMiddlewareStructure($middleware);
            }

            return $instance->handle($request, fn(Request $req) => $next($req));
        };
    }

    /**
     * Run the middleware pipeline with given middleware and kernel.
     *
     * Catches exceptions, throwing them in non-production or returning 500 response in production.
     *
     * @param Request $request
     * @param Response $response
     * @param array<string> $middlewares List of middleware class names.
     * @param callable|null $kernel Optional final callable after middleware.
     * @return Response
     *
     * @throws MiddlewareException On unexpected errors in non-production.
     * @throws MiddlewareMustReturnResponse If middleware pipeline does not return a Response instance.
     */
    private function doHandle(Request $request, Response $response, array $middlewares, ?callable $kernel = null): Response
    {
        $kernel = $kernel ?? fn () => $response;

        try {
            $result = (new Pipeline())
                ->send($request, $response)
                ->through(array_map(fn($m) => $this->adaptMiddleware($m), $middlewares))
                ->then($kernel);

            if (!$result instanceof Response) {
                throw new MiddlewareMustReturnResponse();
            }

            return $result;
        } catch (\Throwable $e) {
            if (config('app.env', 'production') !== 'production') {
                throw new MiddlewareException($e->getMessage(), $e->getCode(), $e);
            }

            $response->setStatusCode(500);
            $response->setContent(json_encode([
                'error' => 'Server Error',
                'detail' => $e->getMessage() . ' at ' . $e->getFile() . ' on line ' . $e->getLine(),
            ]));

            return $response;
        }
    }
}
