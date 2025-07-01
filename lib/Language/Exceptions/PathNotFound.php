<?php

namespace NovaFrame\Language\Exceptions;

use InvalidArgumentException;

class PathNotFound extends InvalidArgumentException
{
    public function __construct($path)
    {
        parent::__construct("Language file '$path' not found.");
    }
}
