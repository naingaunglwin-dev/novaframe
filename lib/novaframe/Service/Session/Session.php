<?php

namespace Nova\Service\Session;

use TypeError;

class Session implements SessionInterface
{
    /**
     * @var object The session configuration.
     */
    private object $config;

    /**
     * @var array The session global data.
     */
    private array $global = [];

    /**
     * Session constructor.
     *
     * @param string|null $name (Optional) The name of the session.
     */
    public function __construct(string $name = null)
    {
        $config = config('session');

        $config = (object) [
            'name'     => $name ?? $config['name'],
            'secure'   => $config['secure'] ?? true,
            'httponly' => $config['httponly'] ?? true,
            'sameSite' => $config['sameSite'] ?? 'Strict',
            'timeout'  => $config['timeout'] ?? 3600,
        ];

        $this->checkConfig($config);

        $this->config = $config;

        if (session_status() === PHP_SESSION_NONE) {

            $this->config();

            session_start();

            $this->regenerate();
        }

        $this->init();
    }

    /**
     * Initializes the session.
     *
     * @return void
     */
    private function init(): void
    {
        $this->global = $_SESSION ?? [];
    }

    /**
     * Configures the session.
     *
     * @return void
     */
    private function config(): void
    {
        session_set_cookie_params([
            'secure'   => $this->config->secure,
            'httponly' => $this->config->httponly,
            'samesite' => $this->config->sameSite,
        ]);

        session_name($this->config->name);
    }

    /**
     * Regenerates the session ID if the timeout has exceeded.
     *
     * @return void
     */
    private function regenerate(): void
    {
        $lastAccessTime = $_SESSION['last_access_time'] ?? 0;

        $elapsedTime = time() - $lastAccessTime;

        if ($elapsedTime > $this->config->timeout) {
            session_regenerate_id(true);

            $_SESSION['last_access_time'] = time();
        }

    }

    /**
     * Checks if the session configuration is valid.
     *
     * @param object $config The session configuration object.
     *
     * @return void
     * @throws TypeError
     */
    private function checkConfig(object $config): void
    {
        if (isset($config->name) && gettype($config->name) !== 'string') {
            throw new TypeError("Session name must be string type");
        }

        if (isset($config->secure) && gettype($config->secure) !== 'boolean') {
            throw new TypeError("Secure flag must be boolean type");
        }

        if (isset($config->httpOnly) && gettype($config->httpOnly) !== 'boolean') {
            throw new TypeError("httpOnly flag must be boolean type");
        }

        if (isset($config->sameSite) && gettype($config->sameSite) !== 'string') {
            throw new TypeError("sameSite attribute must be string type");
        }

        if (isset($config->timeOut) && gettype($config->timeOut) !== 'integer') {
            throw new TypeError("TimeOut must be integer type");
        }
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, bool $overwrite = false): void
    {
        if (isset($this->global[$key]) && !$overwrite) {
            return;
        }

        $this->global[$key] = encrypt($value);

        $this->assignToGlobalSession();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $data = isset($this->global[$key])
            ? $this->checkKeyIsLastAccessTime($key, $this->global[$key])
            : $default;

        $this->handleFlash();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $data = [];

        if (!empty($this->global)) {
            foreach ($this->global as $key => $value) {
                $data[$key] = $this->checkKeyIsLastAccessTime($key, $value);
            }
        }

        $this->handleFlash();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function destroy(string $key): void
    {
        if (isset($this->global[$key])) {
            unset($this->global[$key]);
        }

        $this->assignToGlobalSession();
    }

    /**
     * @inheritDoc
     */
    public function destroyAll(): void
    {
        session_destroy();

        $this->global = [];

        $this->assignToGlobalSession();
    }

    /**
     * @inheritDoc
     */
    public function isSecure(): bool
    {
        return $this->config->secure === true;
    }

    /**
     * @inheritDoc
     */
    public function flash(string $key, mixed $value): void
    {
        $this->set($key, $value);

        if (!$this->has("_flash")) {
            $this->set("_flash", []);
        }

        $this->push("_flash", $key);
    }

    /**
     * @inheritDoc
     */
    public function push(string $key, mixed $value): void
    {
        $old = $this->get($key, []);

        if (!is_array($old)) {
            throw new \BadMethodCallException("Cannot push new value into non array data");
        }

        $old[] = $value;

        $this->set($key, $old);
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->global[$key]);
    }

    /**
     * Handles flash messages by decrypting and destroying them.
     *
     * This method retrieves flash messages stored in the `_flash` session key,
     * decrypts them, and then removes them from the session. Finally, it deletes
     * the `_flash` key itself.
     *
     * @return void
     */
    private function handleFlash(): void
    {
        $flash = $this->global["_flash"] ?? [];

        if (!empty($flash)) {
            $flash = decrypt($flash);
            foreach ($flash as $f) {
                $this->destroy($f);
            }
        }

        $this->destroy("_flash");
    }

    /**
     * Assigns the global session data to the $_SESSION superglobal.
     *
     * @return void
     */
    private function assignToGlobalSession(): void
    {
        $_SESSION = $this->global;
    }

    /**
     * Checks if the key represents the last access time, and decrypts the value if necessary.
     *
     * @param string $key   The session key.
     * @param mixed  $value The session value.
     *
     * @return mixed The decrypted session value if the key is not the last access time, otherwise the original value.
     */
    private function checkKeyIsLastAccessTime(string $key, mixed $value): mixed
    {
        if ($key === 'last_access_time') {
            return $value;
        }

        return decrypt($value);
    }
}
