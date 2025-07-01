<?php

namespace NovaFrame\Route\Exceptions;

use InvalidArgumentException;

class UnsupportedHttpMethod extends InvalidArgumentException
{
    public function __construct($httpMethod)
    {
        parent::__construct("Invalid http method '{$httpMethod}'.");
    }
}
