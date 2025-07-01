<?php

namespace NovaFrame\Http\Exceptions;

class UnsupportedHttpStatus extends HttpException
{
    public function __construct($method)
    {
        parent::__construct(500, 'Unsupported http status: ' . $method);
    }
}
