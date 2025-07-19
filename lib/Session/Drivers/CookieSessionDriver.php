<?php

namespace NovaFrame\Session\Drivers;

use NovaFrame\Facade\Cookie;

class CookieSessionDriver extends AbstractDriver
{
    /**
     * CookieSessionDriver constructor.
     *
     * @param array $config Session configuration including:
     *                      - expire: int
     *                      - encrypt: bool
     *                      - session_path: string
     *                      - domain: string
     *                      - secure: bool
     *                      - httponly: bool
     *                      - samesite: string
     */
    public function __construct(protected array $config)
    {
        parent::__construct($config);
    }

    /**
     * Start the session by reading data from the session cookie.
     *
     * @return bool
     */
    public function start(): bool
    {
        if ($this->isStarted()) {
            return true;
        }

        if (!$this->id) {
            $this->id = $this->generateId();
        }

        $cookieName = $this->getName();

        if (Cookie::has($cookieName)) {
            $data = Cookie::get($cookieName);

            if (!empty($data)) {
                $this->data = $data;
            }
        }

        $this->started = true;

        return true;
    }

    /**
     * Read the session data associated with the given ID.
     *
     * @param string $id Session ID
     * @return string Serialized session data
     */
    public function read(string $id): string
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        return serialize($this->data);
    }

    /**
     * Write session data to the cookie.
     *
     * @param string $id Session ID
     * @param mixed $data Serialized session data
     * @return bool
     */
    public function write(string $id, $data): bool
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $this->data = unserialize($data);

        Cookie::set($this->getName(), $this->data);

        return true;
    }

    /**
     * Destroy the session by deleting the cookie.
     *
     * @param string $id Session ID
     * @return bool
     */
    public function destroy(string $id): bool
    {
        Cookie::expire($this->getName());

        $this->data = [];

        return true;
    }

    /**
     * Close the session and persist the current session data to cookie.
     *
     * @return bool
     */
    public function close(): bool
    {
        if ($this->isStarted()) {
            $this->write($this->id, serialize($this->data));
            $this->started = false;
        }

        return true;
    }

    /**
     * Generate a new session ID and rewrite the session cookie.
     *
     * @param bool $deleteOldSession Ignored in cookie-based sessions
     * @return string The new session ID
     */
    public function regenerateId(bool $deleteOldSession = false): string
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $this->id = $this->generateId();
        $this->write($this->id, serialize($this->data));

        return $this->id;
    }

    /**
     * Garbage collection (not applicable for cookie-based sessions).
     *
     * @param int $lifetime
     * @return bool
     */
    public function gc(int $lifetime): bool
    {
        return true;
    }
}