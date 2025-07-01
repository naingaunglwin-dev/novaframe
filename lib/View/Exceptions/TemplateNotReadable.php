<?php

namespace NovaFrame\View\Exceptions;

use InvalidArgumentException;

class TemplateNotReadable extends InvalidArgumentException
{
    public function __construct($template)
    {
        parent::__construct("The template '{$template}' is not readable.");
    }
}
