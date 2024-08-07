<?php

namespace Nova\Service\Cookie;

class Cookie
{
    /**
     * The default expiration time for cookies in seconds.
     *
     * @var int
     */
    private int $expire;

    /**
     * The default path for cookies.
     *
     * @var string
     */
    private string $path;

    /**
     * The default domain for cookies.
     *
     * @var string
     */
    private string $domain;

    /**
     * Whether cookies should only be sent over secure connections.
     *
     * @var bool
     */
    private bool $secure;

    /**
     * Whether cookies should be accessible only through HTTP(S) requests.
     *
     * @var bool
     */
    private bool $httponly;

    /**
     * An array containing all currently available cookies.
     *
     * @var array
     */
    private array $globals;

    /**
     * Cookie constructor.
     *
     * Initializes default cookie settings from configuration.
     */
    public function __construct()
    {
        $this->expire   = config('cookie.expire');
        $this->path     = config('cookie.path');
        $this->domain   = config('cookie.domain');
        $this->secure   = config('cookie.secure');
        $this->httponly = config('cookie.httponly');

        $this->assignGlobalToLocalProperty();
    }

    /**
     * Sets a cookie with the specified name and value, using default or provided settings.
     *
     * @param string $name The name of the cookie.
     * @param mixed $value The value of the cookie.
     * @param int|null $expire The expiration time of the cookie in seconds. Default is null (uses default).
     * @param string|null $path The path on the server in which the cookie will be available. Default is null (uses default).
     * @param string|null $domain The domain that the cookie is available to. Default is null (uses default).
     * @param bool|null $secure Whether the cookie should only be transmitted over secure connections. Default is null (uses default).
     * @param bool|null $httponly Whether the cookie should only be accessible through HTTP(S) requests. Default is null (uses default).
     */
    public function set(string $name, mixed $value, int $expire = null, string $path = null, string $domain = null, bool $secure = null, bool $httponly = null): void
    {
        $expire = $expire ?? $this->expire;
        $path = $path ?? $this->path;
        $domain = $domain ?? $this->domain;
        $secure = $secure ?? $this->secure;
        $httponly = $httponly ?? $this->httponly;

        $this->doSet($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Assigns the global $_COOKIE array to the local $globals property.
     *
     * @return void
     */
    private function assignGlobalToLocalProperty(): void
    {
        $this->globals = $_COOKIE ?? [];
    }

    /**
     * Sets a cookie using the specified parameters.
     *
     * @param string $name The name of the cookie.
     * @param mixed $value The value of the cookie.
     * @param int $expire The expiration time of the cookie in seconds.
     * @param string $path The path on the server in which the cookie will be available.
     * @param string $domain The domain that the cookie is available to.
     * @param bool $secure Whether the cookie should only be transmitted over secure connections.
     * @param bool $httponly Whether the cookie should only be accessible through HTTP(S) requests.
     */
    private function doSet(string $name, mixed $value, int $expire, string $path, string $domain, bool $secure, bool $httponly): void
    {
        setcookie($name, $value, $this->getFinalTime($expire), $path, $domain, $secure, $httponly);

        $this->assignGlobalToLocalProperty();
    }

    /**
     * Retrieves the value of the specified cookie.
     *
     * @param string $name The name of the cookie.
     * @return mixed|null The value of the cookie if it exists, or null otherwise.
     */
    public function get(string $name): mixed
    {
        return $this->globals[$name] ?? null;
    }

    /**
     * Checks if a cookie with the specified name exists.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return isset($this->globals[$name]);
    }

    /**
     * Destroys (deletes) the specified cookie.
     *
     * @param string $name The name of the cookie to destroy.
     */
    public function destroy(string $name): void
    {
        $this->set($name, null, time() - 3600);
    }

    /**
     * Calculates the final expiration time for a cookie based on the current time and the specified expiration time.
     *
     * @param int $expire The expiration time of the cookie in seconds.
     * @return int The final expiration time of the cookie.
     */
    private function getFinalTime(int $expire): int
    {
        return time() + $expire;
    }
}
