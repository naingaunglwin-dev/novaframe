<?php

namespace Nova\Exception\Exceptions;

class FileException extends \OutOfBoundsException
{
    public static function pathNotFound(string $path): static
    {
        return new static("File: {$path} is not found or Not a valid file");
    }

    public static function pageNotFound(string $page): static
    {
        return new static("Page: {$page} is not found");
    }
}
