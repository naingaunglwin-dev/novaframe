<?php

namespace NovaFrame\Middleware;

use NovaFrame\Http\Request;

abstract class Middleware
{
    /**
     * @param Request $request The current HTTP request.
     * @param \Closure $next Callback to the next middleware.
     */
    abstract public function handle(Request $request, \Closure $next);
}
