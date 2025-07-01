<?php

namespace NovaFrame\Route\Exceptions;

use RuntimeException;

class RouteNotFound extends RuntimeException
{
    public function __construct(string $route, ?string $method = null)
    {
        $message = "Route '{$route}' not found";

        if ($method) {
            $message .= " for method '{$method}'";
        }

        parent::__construct($message);
    }
}