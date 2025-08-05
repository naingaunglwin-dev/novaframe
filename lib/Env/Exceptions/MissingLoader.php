<?php

namespace NovaFrame\Env\Exceptions;

class MissingLoader extends EnvRuntimeException
{
    public function __construct(string $extension)
    {
        parent::__construct("No loader registered for the .$extension extension. Use LoaderRegistry::register() to add a loader for '$extension'.");
    }
}
