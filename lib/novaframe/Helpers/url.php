<?php

if (!function_exists('baseUrl')) {
    /**
     * Get the base URL for the application.
     *
     * @param string $url The path to append to the base URL.
     * @return string The complete URL.
     */
    function baseUrl(string $url = ''): string
    {
        $request = \Nova\HTTP\IncomingRequest::createFromGlobals();

        $baseUrl = env('APP_BASE_URL', $request->getBaseUrl());

        if (str_ends_with($baseUrl, '/')) {
            $baseUrl = substr($baseUrl, 0, -1);
        }

        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return $baseUrl . $url;
    }
}

if (!function_exists('url')) {
    /**
     * Generate the absolute root URL for a given path.
     *
     * This function generates the absolute root URL for a given path, optionally appending it to the base URL
     * defined in the environment configuration. It handles cases where the base URL already ends with a slash.
     *
     * @param string $url The path to append to the root URL. Default is null.
     * @return string The absolute root URL.
     */
    function url(string $url = ''): string
    {
        $request = \Nova\HTTP\IncomingRequest::createFromGlobals();

        $current = $request->getRequestUri();

        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return baseUrl($current . $url);
    }
}
