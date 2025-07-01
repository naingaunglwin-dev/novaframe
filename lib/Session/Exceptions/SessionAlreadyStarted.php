<?php

namespace NovaFrame\Session\Exceptions;

use RuntimeException;

class SessionAlreadyStarted extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Session already started");
    }
}
