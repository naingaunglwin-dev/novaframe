<?php

namespace NovaFrame\Http\Exceptions;

use NovaFrame\Http\Exceptions\HttpException;

class PathNotFound extends HttpException
{
    public function __construct($path)
    {
        parent::__construct(500, 'Path not found: ' . $path);
    }
}
