<?php

namespace NovaFrame\Env\Exceptions;

class InvalidEnvLine extends EnvRuntimeException
{
    public function __construct(string $file, string $line)
    {
        parent::__construct("Invalid env line at $file($line)");
    }
}
