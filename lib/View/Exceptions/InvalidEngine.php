<?php

namespace NovaFrame\View\Exceptions;

use InvalidArgumentException;

class InvalidEngine extends InvalidArgumentException
{
    public function __construct($engine)
    {
        parent::__construct("The engine '$engine' is not a valid engine.");
    }
}
