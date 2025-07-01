<?php

namespace NovaFrame\Session\Drivers;

class NativeSessionDriver extends AbstractDriver
{
    /**
     * Starts the native PHP session and initializes internal state.
     *
     * @return bool
     */
    public function start(): bool
    {
        if ($this->isStarted()) {
            return true;
        }

        session_set_cookie_params([
            'lifetime' => $this->config['lifetime'] ?? 0,
            'path'     => $this->config['session_path'] ?? '/',
            'domain'   => $this->config['domain'] ?? '',
            'secure'   => $this->config['secure'] ?? true,
            'httponly' => $this->config['httponly'] ?? false,
            'samesite' => $this->config['samesite'] ?? 'Lax',
        ]);

        session_name($this->config['name'] ?? 'novaframe_session');

        if ($this->getId() !== '' && $this->getId() !== '0') {
            session_id($this->getId());
        }

        $result = session_start();

        if ($result) {
            $this->started = true;
            $this->id = $this->generateId();
        }

        return true;
    }

    /**
     * Reads the session data by serializing $_SESSION.
     *
     * @param string $id
     * @return string
     */
    public function read(string $id): string
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        return serialize($_SESSION);
    }

    /**
     * Writes serialized session data to $_SESSION.
     *
     * @param string $id
     * @param mixed $data
     * @return bool
     */
    public function write(string $id, $data): bool
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $_SESSION = [];

        if (!empty($data)) {
            $unserialized = unserialize($data);

            if (is_array($unserialized)) {
                $_SESSION = $unserialized;
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Destroys the session, clears $_SESSION and invalidates the cookie.
     *
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        return session_destroy();
    }

    /**
     * Closes the session and writes data to storage.
     *
     * @return bool
     */
    public function close(): bool
    {
        if ($this->isStarted()) {
            $this->started = false;
            return session_write_close();
        }

        return true;
    }

    /**
     * Regenerates the session ID.
     *
     * @param bool $deleteOldSession
     * @return string The new session ID
     */
    public function regenerateId(bool $deleteOldSession = false): string
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        session_regenerate_id($deleteOldSession);
        $this->id = session_id();

        return $this->id;
    }

    /**
     * Sets a custom session ID.
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        parent::setId($id);
        session_id($id);
    }

    /**
     * Sets the session name.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        parent::setName($name);
        session_name($name);
    }

    /**
     * No-op for garbage collection; handled by PHP automatically.
     *
     * @param int $lifetime
     * @return bool
     */
    public function gc(int $lifetime): bool
    {
        return true;
    }
}
