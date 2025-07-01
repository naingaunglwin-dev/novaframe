<?php

if (!function_exists('route')) {
    function route(string $name): string
    {
        /** @var \NovaFrame\Route\RouteCollection $collection */
        $collection = app('routes');

        return baseurl($collection->getRouteByName($name));
    }
}
