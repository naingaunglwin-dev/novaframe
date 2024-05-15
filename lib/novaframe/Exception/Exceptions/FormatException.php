<?php

namespace Nova\Exception\Exceptions;

class FormatException extends \InvalidArgumentException
{
    public static function NotAllowedVarNameFormat(string $name): static
    {
        return new static("Not allowed variable name is used: $name");
    }
}
