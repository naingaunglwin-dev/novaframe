<?php

use NovaFrame\Route\Exceptions\MissingRouteParameter;

if (!function_exists('route')) {
    function route(string $name, array $param = []): string
    {
        /** @var \NovaFrame\Route\RouteCollection $collection */
        $collection = app('routes');
        $segments = $collection->getRouteByName($name);

        $url = [];

        foreach ($segments as $segment) {
            if (preg_match('/\{([^{}]+)\}/', $segment, $match)) {
                $key = $match[1];

                if (!isset($param[$key])) {
                    throw new MissingRouteParameter($key);
                }

                $url[] = $param[$key];
            } else {
                $url[] = $segment;
            }
        }

        return baseurl(implode('/', $url));
    }
}
