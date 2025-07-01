<?php

namespace NovaFrame\Validation\Exceptions;

class UnknownValidationRule extends \InvalidArgumentException
{
    public function __construct(string $rule)
    {
        parent::__construct("Unknown validation rule '{$rule}'.");
    }
}
