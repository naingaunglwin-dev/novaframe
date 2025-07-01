<?php

namespace NovaFrame\Log\Exceptions;

use InvalidArgumentException;

class InvalidLogLevel extends InvalidArgumentException
{
    public function __construct($level)
    {
        parent::__construct("Invalid log level: {$level}");
    }
}
