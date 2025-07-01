<?php

namespace App\Http\Middlewares;

use NovaFrame\Facade\Session;
use NovaFrame\Middleware\Middleware;
use NovaFrame\Http\Request;

class Form extends Middleware
{
    /**
     * @param Request $request The HTTP request object.
     * @param \Closure $next The next middleware closure.
     * @return mixed The result of processing the middleware.
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        if (in_array($method = $request->method(true), ['post', 'get'])) {
            Session::flash('old', $method === 'get' ? $request->query() : $request->post());
        }

        return $next($request);
    }
}
