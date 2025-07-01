<?php

namespace NovaFrame\Middleware\Exceptions;

use RuntimeException;

class MiddlewareMustReturnResponse extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Middleware must return a Response");
    }
}
