<?php

namespace Nova\Middleware;

use Closure;
use Nova\HTTP\IncomingRequest;

abstract class Middleware
{
    /**
     * Handle the middleware.
     *
     * This method should be implemented by concrete middleware classes to process HTTP requests.
     *
     * @param IncomingRequest $request The HTTP request object.
     * @param Closure $next            The next middleware closure.
     * @return mixed                   The result of processing the middleware.
     */
    abstract public function handle(IncomingRequest $request, Closure $next): mixed;
}
