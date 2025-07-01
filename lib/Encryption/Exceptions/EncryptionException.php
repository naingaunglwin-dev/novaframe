<?php

namespace NovaFrame\Encryption\Exceptions;

use PHPUnit\Event\Code\Throwable;
use RuntimeException;

class EncryptionException extends RuntimeException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
