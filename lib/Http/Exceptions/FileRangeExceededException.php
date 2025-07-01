<?php

namespace NovaFrame\Http\Exceptions;

use NovaFrame\Http\Exceptions\HttpException;

class FileRangeExceededException extends HttpException
{
    public function __construct($start, $end, $filesize)
    {
        parent::__construct(500, 'Range ' . $start . ' to ' . $end . ' exceeded filesize: ' . $filesize);
    }
}
