<?php

/**
 * This file is part of NOVA FRAME framework
 *
 * @copyright (c) Naing Aung Lwin
 * @link https://github.com/naingaunglwin-dev/novaframe
 * @licence MIT
 */

namespace Nova\HTTP;

class DynamicParameters
{
    /**
     * The array containing dynamic key-value pairs.
     *
     * @var array An associative array where keys are request URIs and values are arrays of dynamic key-value pairs.
     */
    private static array $parameters = [];

    /**
     * Sets the dynamic key-value pairs for a specific request URI.
     *
     * @param array $parameters An associative array of key-value pairs to be associated with the request URI.
     * @return void
     */
    public function set(array $parameters): void
    {
        self::$parameters = $parameters;
    }

    /**
     * Retrieves dynamic key-value pairs for the current request URI or a specific key.
     *
     * If a specific key is provided, retrieves the value associated with that key in the current request URI's data.
     * If no key is provided, retrieves all key-value pairs associated with the current request URI.
     *
     * @param string $key The key whose value should be retrieved.
     * @return mixed If value with key exists, return the value associated with that key; otherwise, returns null
     */
    public function get(string $key): mixed
    {
        $request = IncomingRequest::createFromGlobals();
        return self::$parameters[$request->getRequestUri()][$key] ?? null;
    }

    /**
     * Get all dynamic parameters
     *
     * @return array
     */
    public function getAll(): array
    {
        return self::$parameters;
    }
}
