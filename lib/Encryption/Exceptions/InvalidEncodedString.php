<?php

namespace NovaFrame\Encryption\Exceptions;

class InvalidEncodedString extends EncryptionException
{
    public function __construct()
    {
        parent::__construct("Invalid base64 string");
    }
}
