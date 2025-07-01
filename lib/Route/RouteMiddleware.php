<?php

namespace NovaFrame\Route;

use NovaFrame\Http\Request;
use NovaFrame\Http\Response;
use NovaFrame\Middleware\Handler;
use NovaFrame\Middleware\Middleware;

class RouteMiddleware
{
    /**
     * RouteMiddleware constructor.
     *
     * @param Handler $middleware The middleware handler that manages middleware resolution and execution
     */
    public function __construct(private Handler $middleware)
    {
    }

    /**
     * Handle the given middleware stack and return a modified Response.
     *
     * If `$global` is true, only global middleware will be run. Otherwise, the provided
     * middleware(s) will be executed in the request pipeline.
     *
     * @param Request                                $request     The current HTTP request instance
     * @param Response|null                          $response    The response instance to mutate; a new one is created if null
     * @param Middleware|string|array|null           $middlewares Middleware(s) to run (alias name, class, or list of them)
     * @param bool                                   $global      Whether to run only global middleware
     *
     * @return Response The modified or original response after middleware processing
     */
    public function handle(Request $request, ?Response $response = null, Middleware|string|array|null $middlewares = null, bool $global = false): Response
    {
        $response ??= response();

        if ($global) {
            return $this->middleware->handleGlobals($request, $response);
        } else {
            $middlewares = $this->normalize($middlewares);

            return $this->middleware->handle($request, $response, $middlewares);
        }
    }

    /**
     * Normalize middleware input to ensure it's an array.
     *
     * @param mixed $middlewares A string, array, or null representing middleware(s)
     *
     * @return array A normalized array of middleware
     */
    private function normalize($middlewares)
    {
        if (empty($middlewares)) {
            return [];
        }

        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        return $middlewares;
    }
}
