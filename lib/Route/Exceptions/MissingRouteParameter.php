<?php

namespace NovaFrame\Route\Exceptions;

use InvalidArgumentException;

class MissingRouteParameter extends InvalidArgumentException
{
    public function __construct(string $parameter)
    {
        parent::__construct("Missing route parameter: {" . $parameter . "}");
    }
}
