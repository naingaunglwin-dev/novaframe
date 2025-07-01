<?php

namespace NovaFrame\Middleware\Exceptions;

use RuntimeException;
use Throwable;

class MiddlewareException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
