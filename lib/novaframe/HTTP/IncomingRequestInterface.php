<?php

namespace Nova\HTTP;

interface IncomingRequestInterface
{
    /**
     * Retrieves the request scheme (HTTP or HTTPS).
     *
     * @return string|null The request scheme or null if not set.
     */
    public function getScheme(): ?string;

    /**
     * Retrieve the protocol version
     *
     * @return string|null
     */
    public function getProtocolVersion(): ?string;

    /**
     * Retrieves the host from the request header.
     *
     * @return string|null The host name or null if not found.
     */
    public function getHost(): ?string;

    /**
     * Retrieves the port number from the server configuration.
     *
     * @return int|null The port number or null if not found.
     */
    public function getPort(): ?int;

    /**
     * Retrieves the server address from the server configuration.
     *
     * @return string|null The server address or null if not found.
     */
    public function getServerAddress(): ?string;

    /**
     * Retrieves the base URL and appends the provided URL segment.
     *
     * @param string $url The URL segment to append (default: '').
     *
     * @return string|null The complete base URL with the provided segment or null if not found.
     */
    public function getBaseUrl(string $url): ?string;

    /**
     * Retrieves the full URL of the request.
     *
     * @return string|null The absolute URL or null if not found.
     */
    public function getFullUrl(): ?string;

    /**
     * Get the request URI.
     *
     * Retrieves the request URI from the server environment variables. If the application
     * has a base URL set in the environment configuration, it trims the base URL from
     * the full URL to obtain the relative URI. Optionally, it can include or exclude
     * the query string from the URI.
     *
     * @param bool $query Whether to include the query string in the URI.
     * @return string|null The request URI, or null if not found.
     */
    public function getRequestUri(bool $query = false): ?string;

    /**
     * Retrieves the HTTP method used in the request.
     *
     * @param bool $lowercase To return method in lowercase
     *
     * @return string|null The request method or null if not found.
     */
    public function getMethod(bool $lowercase = false): ?string;

    /**
     * Retrieves the query string from the request.
     *
     * @return string|null The query string or null if not found.
     */
    public function getQueryString(): ?string;

    /**
     * Retrieves data from the request.
     *
     * @param string $name      The name of the data.
     * @param string|null $method    The HTTP method ('GET', 'POST', 'HEAD', 'PUT', 'PATCH', 'DELETE'). (default: null)
     *                          If method is null, it will find from POST, GET by default.
     * @param mixed  $default   The default value if input not found (default: null).
     * @param bool   $sanitize  Whether to sanitize the input (default: true).
     *
     * @return mixed The requested input value or null if not found.
     */
    public function getData(string $name, string $method = null, mixed $default = null, bool $sanitize = true): mixed;

    /**
     * Retrieves a file uploaded via the HTTP request.
     *
     * @param string $name The name of the file input field.
     * @param bool $sanitize Whether to sanitize the file data (default is true).
     * @return mixed The file data associated with the provided name, or null if
     *                     no file with the given name exists or if sanitization is disabled
     *                     and the file data is not found in the global file data array.
     */
    public function file(string $name, bool $sanitize = true): mixed;

    /**
     * Retrieves dynamic data(route param) associated with the current request.
     *
     * If the provided key exists in the dynamic data(route param) set for the current request,
     * returns the corresponding value. If no key is provided or the key does not exist,
     * returns null.
     *
     * @param string $key The key for which to retrieve dynamic data.
     * @return mixed The value associated with the provided key, or null if the key
     *                    does not exist or no key is provided.
     */
    public function getRouteParam(string $key): mixed;
}
