<?php

namespace Nova\Middleware;

use App\HTTP\Kernel;
use Nova\HTTP\IncomingRequestInterface;

class MiddlewareHandler
{
    /**
     * Handle the incoming request with the specified middleware.
     *
     * @param IncomingRequestInterface $request The incoming request.
     * @param string|array|Middleware $middleware The middleware(s) to handle.
     * @return mixed The response from handling the middleware.
     */
    public function handle(IncomingRequestInterface $request, string|array|Middleware $middleware): mixed
    {
        return $this->doHandle($request, $middleware);
    }

    /**
     * Handle the incoming request with the default middlewares defined in the application kernel.
     *
     * @param IncomingRequestInterface $request The incoming request.
     * @return mixed The response from handling the default middlewares.
     */
    public function handleDefault(IncomingRequestInterface $request): mixed
    {
        $kernel = new Kernel();

        $middlewares = $kernel->middlewares;

        return $this->doHandle($request, $middlewares);
    }


    /**
     * Perform the actual handling of the incoming request with the specified middleware(s).
     *
     * @param IncomingRequestInterface $request The incoming request.
     * @param array|string|Middleware  $middlewares The middleware(s) to handle.
     * @return mixed The response from handling the middleware.
     */
    private function doHandle(IncomingRequestInterface $request, array|string|Middleware $middlewares): mixed
    {
        $response = $request;

        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        if (empty($middlewares)) {
            return $request;
        }

        foreach ($middlewares as $middleware) {
            if (is_string($middleware)) {
                $middleware = new $middleware();
            }

            if ($middleware instanceof Middleware) {
                $next = function ($request) use (&$response) {
                    $response = $request;

                    return $request;
                };

                $response = $middleware->handle($request, $next);
            }
        }

        return $response;
    }
}
