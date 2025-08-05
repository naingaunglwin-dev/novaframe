<?php

namespace NovaFrame\Env\Exceptions;

use InvalidArgumentException;

class PathNotFound extends InvalidArgumentException
{
    public function __construct($path)
    {
        parent::__construct("Path '{$path}' not found");
    }
}
