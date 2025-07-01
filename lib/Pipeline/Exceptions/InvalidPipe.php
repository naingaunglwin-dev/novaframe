<?php

namespace NovaFrame\Pipeline\Exceptions;

use RuntimeException;

class InvalidPipe extends RuntimeException
{
    public function __construct($pipe)
    {
        parent::__construct('Invalid pipe provided: \'' . $pipe . "'");
    }
}
