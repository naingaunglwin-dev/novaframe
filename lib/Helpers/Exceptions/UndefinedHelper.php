<?php

namespace NovaFrame\Helpers\Exceptions;

use InvalidArgumentException;

class UndefinedHelper extends InvalidArgumentException
{
    public function __construct(string $helper)
    {
        parent::__construct("Undefined helper '{$helper}'");
    }
}
