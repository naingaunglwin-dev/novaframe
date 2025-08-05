<?php

namespace NovaFrame\Env\Exceptions;

class UnableToOpenFileException extends EnvRuntimeException
{
    public function __construct(string $file)
    {
        parent::__construct("Unable to open file '$file'");
    }
}
