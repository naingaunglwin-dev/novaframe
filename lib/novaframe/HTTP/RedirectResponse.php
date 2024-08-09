<?php

namespace Nova\HTTP;

class RedirectResponse extends Response
{
    /**
     * RedirectResponse constructor.
     *
     * @param string $url      The URL to redirect to.
     * @param int    $status   The HTTP status code for the redirect (default is 302).
     * @param array  $headers An optional array of additional headers to set.
     */
    public function __construct(string $url = '', int $status = 302, array $headers = [])
    {
        if (!empty($url) && !in_array("Location", $headers)) {
            $headers = array_merge(['Location' => $url], $headers);
        }

        parent::__construct("", $status, $headers);
    }

    /**
     * Set the URL for redirection.
     *
     * @param string $url The URL to redirect to.
     *
     * @return RedirectResponse Returns the current instance for method chaining.
     */
    public function to(string $url): RedirectResponse
    {
        $this->setHeader("Location", $url);

        return $this;
    }

    /**
     * Set flash data to be available for the next request.
     *
     * This allows you to set flash data either as a single key-value pair or as multiple key-value pairs.
     *
     * If a single key-value pair is provided, the flash data will be stored with the given key and value.
     *
     * If an array is provided, the array should be in the format of `[key => value]`, where each key-value pair
     * will be stored as flash data.
     *
     * @param string|array $key   The key under which the flash data will be stored, or an array of key-value pairs.
     * @param mixed  $value The value of the flash data. This is only used if $key is a string.
     *
     * @return RedirectResponse Returns the current instance for method chaining.
     */
    public function with(string|array $key, $value): RedirectResponse
    {
        $session = session();

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $session->flash($k, $v);
            }
        } else {
            $session->flash($key, $value);
        }

        return $this;
    }
}
