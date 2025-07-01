<?php

return <<<EOT

<?php

namespace %s;

use NovaFrame\Middleware\Middleware;

class %s extends Middleware
{
    /**
     * Handle the middleware.
     *
     * @param \NovaFrame\Http\Request $request The HTTP request object.
     * @param \Closure $next The next middleware closure.
     * @return mixed The result of processing the middleware.
     */
    public function handle(\NovaFrame\Http\Request $request, \Closure $next): mixed
    {
        return $next($request);
    }
}

EOT;
