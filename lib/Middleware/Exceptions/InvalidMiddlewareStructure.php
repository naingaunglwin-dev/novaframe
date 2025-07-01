<?php

namespace NovaFrame\Middleware\Exceptions;

use RuntimeException;

class InvalidMiddlewareStructure extends RuntimeException
{
    public function __construct($middleware)
    {
        parent::__construct("Middleware {$middleware} must have a handle() method.");
    }
}
