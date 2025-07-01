<?php

namespace App\Http\Middlewares;

use NovaFrame\Facade\Session;
use NovaFrame\Http\Request;
use NovaFrame\Middleware\Middleware;

class TrackPreviousUrl extends Middleware
{

    public function handle(Request $request, \Closure $next)
    {
        $current = $request->fullUrl(true);

        if (!$request->isAjax() && !$this->isStatic($current)) {
            $last = Session::get('current_url');

            if ($last !== null && $current !== $last) {
                Session::set('previous_url', $last);
            }

            Session::set('current_url', $current);
        }

        $response =  $next($request);

        return $response;
    }

    protected function isStatic(string $url): bool
    {
        return preg_match('/\.(css|js|png|jpe?g|gif|svg|woff2?|ttf|ico)$/', $url);
    }
}