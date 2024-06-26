<?php

namespace Nova\HTTP;

class IncomingRequest extends Request implements IncomingRequestInterface
{

    /**
     * @inheritDoc
     */
    public function getScheme(): ?string
    {
        return $this->getFromServer('REQUEST_SCHEME');
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): ?string
    {
        return $this->getFromServer('SERVER_PROTOCOL');
    }

    /**
     * @inheritDoc
     */
    public function getHost(): ?string
    {
        return $this->getFromHeader('HOST') ?? $this->getFromServer('HTTP_HOST');
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->getFromServer('SERVER_PORT');
    }

    /**
     * @inheritDoc
     */
    public function getServerAddress(): ?string
    {
        return $this->getFromServer('SERVER_ADDR');
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl(string $url = ''): ?string
    {
        $request = str_replace($this->getScheme() . '://' . $this->getHost(), "", $this->getFullUrl());

        if ($request != $this->getRequestUri()) {
            $request = str_replace($this->getRequestUri(), "", $this->getFullUrl());
        }

        return $this->trimQueryString(
                $request
            ) . $url;
    }

    /**
     * @inheritDoc
     */
    public function getFullUrl(): ?string
    {
        $scheme = $this->getScheme();
        $host   = $this->getHost();

        if ($scheme == 'http' && str_contains($host, 'localhost')) {
            $h = explode(':', $host);

            $host = $h[0];
        }

        return $scheme . '://' . $host . ($this->getPort() != 80 ? ':' . $this->getPort() : '') . $this->getFromServer('REQUEST_URI');
    }

    /**
     * @inheritDoc
     */
    public function getRequestUri(bool $query = false): ?string
    {
        if (!function_exists('env') || !env('APP_BASE_URL')) {
            return $this->globals->server['REQUEST_URI'];
        }

        $url = str_replace(env('APP_BASE_URL'), "", $this->getFullUrl());

        $url = $query ? $url : $this->trimQueryString($url);

        if (!$query && str_ends_with($url, '/')) {
            $url = substr($url, 0, -1);
        }

        if (!str_starts_with($url, '/')) {
            $url = "/$url";
        }

        return $url;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(bool $lowercase = false): ?string
    {
        return $this->returnStringInGivenCase(
            $lowercase ? 'lower' : 'upper',
            $this->getFromServer('REQUEST_METHOD')
        );
    }

    /**
     * @inheritDoc
     */
    public function getQueryString(): ?string
    {
        return $this->globals->server['QUERY_STRING'];
    }

    /**
     * @inheritDoc
     */
    public function getData(string $name, string $method = null, mixed $default = null, bool $sanitize = true): mixed
    {
        $method = strtoupper($method);

        if ($sanitize && !$this->isSanitized()) {
            $this->sanitize();
        }

        if (in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            $method = in_array($method, ['PUT', 'PATCH', 'DELETE']) ? 'body' : strtolower($method);

            return ($sanitize ? $this->sanitized : $this->globals)->{$method}[$name] ?? $default;
        } elseif ($method === 'HEAD') {

            return $this->globals->header[$name] ?? $default;
        } elseif (empty($method) ||empty(trim($method))) {
            foreach (['POST', 'GET'] as $m) {

                return ($sanitize ? $this->sanitized : $this->globals)->{$m}[$name] ?? $default;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function file(string $name, bool $sanitize = true): mixed
    {
        if ($sanitize === true) {

            if ($this->isSanitized() === false) {
                $this->sanitize();
            }

            return $this->sanitized->file[$name] ?? null;
        }

        return $this->globals->file[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getRouteParam(string $key): mixed
    {
        $dynamicParam = new DynamicParameters();

        return $dynamicParam->get($key);
    }

    /**
     * Get a value from $_SERVER.
     *
     * @param string $key The key to retrieve from $_SERVER.
     * @param bool $sanitized Whether to fetch from sanitized ($_SERVER) or globals ($_SERVER).
     * @return mixed|null The value from $_SERVER corresponding to $key, or null if not found.
     */
    public function getFromServer(string $key, bool $sanitized = true): mixed
    {
        $from = $sanitized ? "sanitized" : "globals";

        return $this->{$from}->server[$key] ?? null;
    }

    /**
     * Get a value from headers.
     *
     * @param string $key The key to retrieve from headers.
     * @param bool $sanitized Whether to fetch from sanitized (filtered) headers or raw headers ($_SERVER).
     * @return mixed|null The value from headers corresponding to $key, or null if not found.
     */
    public function getFromHeader(string $key, bool $sanitized = true): mixed
    {
        $from = $sanitized ? "sanitized" : "globals";

        return $this->{$from}->header[$key] ?? null;
    }

    /**
     * Convert a given string into given case, (lower, upper)
     *
     * @param string $case
     * @param string $string
     * @return string
     */
    private function returnStringInGivenCase(string $case, string $string): string
    {
        return match ($case) {
            'lower' => strtolower($string),
            'upper' => strtoupper($string),
        };
    }

    /**
     * Trims the query string from the provided URL, if present.
     *
     * @param string $url The URL to process.
     *
     * @return string The URL with the query string removed, if found.
     */
    private function trimQueryString(string $url): string
    {
        $parsed = parse_url($url);

        return $parsed['path'] ?? $url;
    }
}
