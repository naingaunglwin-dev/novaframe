<?php

namespace NovaFrame\Env\Exceptions;

use RuntimeException;

class InvalidEnvKeyFormat extends RuntimeException
{
    public function __construct(string $key, string $file)
    {
        parent::__construct('Invalid key format is used in env variable: ' . $key . ' in ' . $file);
    }
}
