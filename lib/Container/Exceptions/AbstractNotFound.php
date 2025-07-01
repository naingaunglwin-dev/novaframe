<?php

namespace NovaFrame\Container\Exceptions;

use InvalidArgumentException;

class AbstractNotFound extends InvalidArgumentException
{
    public function __construct(string $abstract)
    {
        parent::__construct("Abstract {$abstract} not found");
    }
}
