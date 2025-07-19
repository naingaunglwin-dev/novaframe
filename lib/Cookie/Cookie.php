<?php

namespace NovaFrame\Cookie;

use NovaFrame\Encryption\Encryption;

class Cookie
{
    /**
     * Holds the cookies for the current request.
     *
     * @var array<string, mixed>
     */
    private array $cookies = [];

    /**
     * Tracks cookies that were explicitly set in PHP to avoid resaving synced ones.
     *
     * @var array<string, bool>
     */
    private array $new = [];

    /**
     * Stores options for each cookie, like 'expires', 'path', etc.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $options = [];

    /**
     * Tracks cookies that have been marked for expiration during the request.
     *
     * @var array<string, bool>
     */
    private array $expires = [];

    /**
     * Flag to determine if cookies should be encrypted.
     *
     * @var bool
     */
    private bool $shouldEncrypt;

    /**
     * Indicates whether any cookies have been modified and need saving.
     *
     * @var bool
     */
    private bool $isDirty = false;

    /**
     * Cookie constructor.
     *
     * Merges provided configuration with application defaults and initializes the cookie state.
     *
     * @param array<string, mixed> $config Custom configuration for the cookie instance.
     */
    public function __construct(private array $config = [])
    {
        $this->config = array_merge(config('session'), $this->config);

        $this->shouldEncrypt = $this->config['encrypt'] ?? false;

        $this->fetch();
    }

    /**
     * Fetches cookies from the $_COOKIE superglobal into the local property.
     *
     * @return void
     */
    private function fetch(): void
    {
        $this->cookies = $_COOKIE;
    }

    /**
     * Synchronizes the local cookie state with the $_COOKIE superglobal.
     * Any cookies present in $_COOKIE but not in the local store are added.
     *
     * @return void
     */
    public function sync(): void
    {
        $global = $_COOKIE;
        $local  = $this->cookies;
        $new    = [];

        foreach ($global as $name => $value) {
            if (!isset($local[$name])) {
                $new[] = [
                    'name' => $name,
                    'value' => $value,
                ];
            }
        }

        if (!empty($new)) {
            $this->set($new);
        }
    }

    /**
     * Sets a cookie value. Can handle a single cookie or an array of cookies.
     *
     * @param array<int, array<string, mixed>>|string $name The name of the cookie or an array of cookies to set.
     * @param mixed $value The value of the cookie if setting a single cookie.
     * @param array<string, mixed> $options The options for the cookie if setting a single cookie.
     * @return void
     */
    public function set(array|string $name, mixed $value = null, array $options = []): void
    {
        if (is_array($name)) {
            foreach ($name as $cookie) {
                $this->new[$cookie['name']]    = true;
                $this->cookies[$cookie['name']] = $this->setValue($cookie['value']);
                $this->options[$cookie['name']] = $cookie['options'] ?? [];
            }

            return;
        }

        $this->new[$name] = true;
        $this->cookies[$name] = $this->setValue($value);
        $this->options[$name] = $options;
        $this->isDirty = true;
    }

    /**
     * Retrieves a cookie value by its name.
     *
     * @param string $name The name of the cookie.
     * @param mixed|null $default The default value to return if the cookie is not found.
     * @return mixed The cookie value or the default value.
     */
    public function get(string $name, $default = null): mixed
    {
        if (in_array($name, $this->expires)) {
            return $default;
        }

        return $this->getValue($name, $default);
    }

    /**
     * Checks if a cookie exists.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * Removes a cookie from the local store.
     * Note: This does not expire the cookie in the browser, it works only for locally
     *
     * @param string $name The name of the cookie to remove.
     * @return void
     */
    public function remove(string $name): void
    {
        unset($this->cookies[$name]);
        unset($this->options[$name]);
    }

    /**
     * Marks a cookie for expiration.
     * The cookie will be removed from the browser on the next save() call.
     *
     * @param string $name The name of the cookie to expire.
     * @return void
     */
    public function expire(string $name): void
    {
        if (!isset($this->cookies[$name])) {
            return;
        }

        $this->options[$name] = [
            'expires' => time() - 3600
        ];

        $this->cookies[$name] = '';
        $this->expires[$name] = true;
        $this->isDirty = true;
    }

    /**
     * Checks if any cookies have been modified and are pending to be saved.
     *
     * @return bool True if there are unsaved cookie changes, false otherwise.
     */
    public function isDirty(): bool
    {
        return $this->isDirty;
    }

    /**
     * Determines if a specific cookie has been marked as expired during this request.
     *
     * @param string $name The name of the cookie to check.
     * @return bool True if the cookie is expired, false otherwise.
     */
    public function isExpired(string $name): bool
    {
        return isset($this->expires[$name]);
    }

    /**
     * Clears all cookies from the local store.
     *
     * @return void
     */
    public function clean(): void
    {
        $this->cookies = [];
        $this->options = [];
    }

    /**
     * Retrieves a cookie's value and then removes or expires it.
     *
     * @param string $name The name of the cookie.
     * @param bool   $expire If true, the cookie will be expired. Otherwise, it's just removed.
     * @return mixed The value of the cookie before it was removed.
     */
    public function pull(string $name, bool $expire = false): mixed
    {
        $value = $this->get($name);

        if ($expire) {
            $this->expire($name);
        } else {
            $this->remove($name);
        }

        return $value;
    }

    /**
     * Gets the default 'secure' setting.
     *
     * @return bool
     */
    public function secure(): bool
    {
        return $this->config['secure'];
    }

    /**
     * Gets the default 'domain' setting.
     *
     * @return string
     */
    public function domain(): string
    {
        return $this->config['domain'];
    }

    /**
     * Gets the default 'path' setting.
     *
     * @return string
     */
    public function path(): string
    {
        return $this->config['path'];
    }

    /**
     * Gets the default 'httponly' setting.
     *
     * @return bool
     */
    public function httponly(): bool
    {
        return $this->config['httponly'];
    }

    /**
     * Gets the default 'samesite' setting.
     */
    public function samesite()
    {
        return $this->config['samesite'];
    }

    /**
     * Saves all queued cookies to the browser by calling setcookie().
     *
     * @return void
     */
    public function save(): void
    {
        if (empty($this->cookies)) {
            return;
        }

        foreach ($this->cookies as $name => $value) {
            if (!isset($this->new[$name])) {
                continue;
            }
            setcookie(
                $name,
                $value,
                [
                    'expires'  => time() + ($this->options[$name]['expires'] ?? $this->config['expire']),
                    'domain'   => $this->options[$name]['domain'] ?? $this->config['domain'],
                    'path'     => $this->options[$name]['path'] ?? $this->config['session_path'],
                    'secure'   => $this->options[$name]['secure'] ?? $this->config['secure'],
                    'httponly' => $this->options[$name]['httponly'] ?? $this->config['httponly'],
                    'samesite' => $this->options[$name]['samesite'] ?? $this->config['samesite'],
                ]
            );
        }
    }

    /**
     * Immediately sends a cookie to the browser.
     * Does not queue it for later `save()`, just sends it directly.
     *
     * @param string $name
     * @param mixed $value
     * @param array<string, mixed> $options
     * @return void
     */
    public function send(string $name, mixed $value, array $options = [])
    {
        $this->cookies[$name] = $this->setValue($value);

        setcookie(
            $name,
            $this->cookies[$name],
            [
                'expires'  => time() + $options['expires'] ?? $this->config['expire'],
                'domain'   => $options['domain'] ?? $this->config['domain'],
                'path'     => $options['path'] ?? $this->config['path'],
                'secure'   => $options['secure'] ?? $this->config['secure'],
                'httponly' => $options['httponly'] ?? $this->config['httponly'],
                'samesite' => $options['samesite'] ?? $this->config['samesite'],
            ]
        );
    }

    /**
     * Prepares a value for storage, encrypting it if required.
     *
     * @param mixed $value The value to prepare.
     * @return mixed The prepared value.
     */
    private function setValue($value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return $this->shouldEncrypt
            ? Encryption::encrypt($value)
            : $value;
    }

    /**
     * Retrieves and processes a value from the local store, decrypting if required.
     *
     * @param string $name The name of the cookie.
     * @param mixed|null $default The default value to return if not found.
     * @return mixed The processed value or the default.
     */
    private function getValue(string $name, $default = null): mixed
    {
        if (!isset($this->cookies[$name])) {
            return $default;
        }

        $value = $this->cookies[$name];

        if ($value === null || $value === '') {
            return $value;
        }

        return $this->shouldEncrypt
            ? Encryption::decrypt($value)
            : $value;
    }
}
