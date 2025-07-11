<?php

namespace NovaFrame\Http;

use NovaFrame\Facade\Session;

class RedirectResponse extends Response
{
    /**
     * RedirectResponse constructor.
     *
     * @param string $url Optional URL to redirect to.
     * @param int $status HTTP status code for redirection (default: 302).
     * @param array<string, string> $headers Optional additional headers.
     */
    public function __construct(string $url = '', int $status = 302, array $headers = [])
    {
        $headers = array_merge($headers, ['Location' => $this->resolveRedirectUrl($url)]);

        parent::__construct('', $status, $headers);
    }

    /**
     * Set a new redirect target URL.
     *
     * @param string $url The target URL.
     *
     * @return $this
     */
    public function to(string $url): RedirectResponse
    {
        $this->setHeader('Location', $this->resolveRedirectUrl($url));

        return $this;
    }

    /**
     * Add flash data to the session that will be available on the next request.
     *
     * @param string $key Key name for the flash data.
     * @param mixed $value Value to flash.
     *
     * @return $this
     */
    public function with(string $key, $value): RedirectResponse
    {
        Session::flash($key, $value);

        return $this;
    }

    /**
     * Resolves a redirect URL, ensuring it's absolute.
     *
     * @param string $url Target URL which may be relative.
     * @return string Absolute URL to be used in the Location header.
     */
    private function resolveRedirectUrl(string $url): string
    {
        $parsed = parse_url($url);

        if (!isset($parsed['scheme'])) {
            return Request::createFromGlobals()->baseurl($url);
        }

        return $url;
    }
}
