<?php

namespace App\HTTP;

class Kernel
{
    /**
     * The array of middleware classes registered in the HTTP kernel.
     * These middlewares are executed on every incoming HTTP request.
     *
     * @var array $middlewares
     */
    public array $middlewares = [];
}
