<?php

namespace App\Http\Middlewares;

use NovaFrame\Facade\Event;
use NovaFrame\Facade\Session;
use NovaFrame\Http\Request;
use NovaFrame\Http\Response;
use NovaFrame\Middleware\Middleware;

class CsrfToken extends Middleware
{
    public function handle(Request $request, \Closure $next)
    {
        $method   = $request->method('true');
        $response = app()->make(Response::class);
        $token    = $request->input('csrf_token') ?: $request->header('X-CSRF-TOKEN');

        if (
            in_array($method, ['post', 'put', 'patch', 'delete'])
            && !Session::validateCsrfToken($token)
        ) {
            Event::defer('csrfValidation', ['response' => $response, 'pass' => false]);

            return $response;
        }

        Event::defer('csrfValidation', ['response' => $response, 'pass' => true]);

        return $next($request);
    }
}
