<?php

return <<<EOT

<?php

namespace %s;

use Nova\Middleware\Middleware;

class %s extends Middleware
{
    /**
     * Handle the middleware.
     *
     * This method should be implemented by concrete middleware classes to process HTTP requests.
     *
     * @param \Nova\HTTP\IncomingRequest $request The HTTP request object.
     * @param \Closure $next The next middleware closure.
     * @return mixed The result of processing the middleware.
     */
    public function handle(\Nova\HTTP\IncomingRequest $request, \Closure $next): mixed
    {
        return $next($request);
    }
}

EOT;
