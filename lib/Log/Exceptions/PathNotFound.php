<?php

namespace NovaFrame\Log\Exceptions;

use InvalidArgumentException;

class PathNotFound extends InvalidArgumentException
{
    public function __construct($path)
    {
        parent::__construct("Log '{$path}' does not exist.");
    }
}
