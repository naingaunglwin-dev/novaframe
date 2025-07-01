<?php

namespace NovaFrame\View\Exceptions;

use BadMethodCallException;

class UnknownSection extends BadMethodCallException
{
    public function __construct()
    {
        parent::__construct("Section does not start yet");
    }
}
