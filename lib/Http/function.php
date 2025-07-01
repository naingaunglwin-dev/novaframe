<?php

use NovaFrame\Http\DownloadResponse;
use NovaFrame\Http\RedirectResponse;
use NovaFrame\Http\Request;
use NovaFrame\Http\Response;

if (!function_exists('request')) {
    /**
     * Get the current HTTP request.
     *
     * @return Request
     */
    function request(): Request
    {
        return Request::createFromGlobals();
    }
}

if (!function_exists('response')) {
    /**
     * Create a new HTTP response.
     *
     * @param string $content
     * @param int $status
     * @param array<string, string> $headers
     * @return Response
     */
    function response(string $content = '', int $status = 200, array $headers = []): Response
    {
        $response = app()->make(Response::class);

        $response->setContent($content);
        $response->setStatusCode($status);
        $response->setHeaders($headers);

        return $response;
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect response to the given URL.
     *
     * @param string $url
     * @param int $status
     * @param array<string, string> $headers
     * @return RedirectResponse
     */
    function redirect(string $url, int $status = 302, array $headers = []): RedirectResponse
    {
        $response = app()->make(Response::class);

        return $response->redirect($url, $status, $headers);
    }
}

if (!function_exists('download')) {
    /**
     * Create a download response.
     *
     * @param string $filePath
     * @param string|null $fileName
     * @param array<string, string> $headers
     * @return DownloadResponse
     */
    function download(string $filePath, ?string $fileName = null, array $headers = []): DownloadResponse
    {
        $response = app()->make(Response::class);

        return $response->download($filePath, $fileName, $headers);
    }
}

if (!function_exists('baseurl')) {
    /**
     * Get the base URL for the current request.
     *
     * @param string $url
     * @return string
     */
    function baseurl(string $url = ''): string
    {
        return request()->baseUrl($url);
    }
}
