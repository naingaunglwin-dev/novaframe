<?php

namespace NovaFrame\View\Exceptions;

use InvalidArgumentException;

class TemplateNotFound extends InvalidArgumentException
{
    public function __construct($path)
    {
        parent::__construct("Template '{$path}' not found");
    }
}
