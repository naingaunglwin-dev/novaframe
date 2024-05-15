<?php

namespace Nova\HTTP;

interface RequestInterface
{
    /**
     * Create a new instance of request using global variables
     *
     * @return static
     */
    public static function createFromGlobals(): static;
}
