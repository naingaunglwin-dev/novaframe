<?php

namespace NovaFrame\Facade\Exceptions;

use RuntimeException;

class EmptyAccessor extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("No accessor is defined");
    }
}
