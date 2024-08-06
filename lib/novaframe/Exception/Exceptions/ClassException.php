<?php

namespace Nova\Exception\Exceptions;

class ClassException extends \OutOfBoundsException
{
    public static function classNotFound(string $class)
    {
        return new static("Class: $class is not found:");
    }
}
