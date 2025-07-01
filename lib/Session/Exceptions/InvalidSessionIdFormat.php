<?php

namespace NovaFrame\Session\Exceptions;

use InvalidArgumentException;

class InvalidSessionIdFormat extends InvalidArgumentException
{
    public function __construct($id)
    {
        parent::__construct('Invalid UUID v4 format for session ID: ' . $id);
    }
}
