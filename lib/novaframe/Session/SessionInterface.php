<?php

namespace Nova\Session;

interface SessionInterface
{
    /**
     * Sets a session value.
     *
     * @param string $key       The key of the session value.
     * @param mixed  $value     The value to be set.
     * @param bool   $overwrite Whether to overwrite the value if it exists
     *
     * @return void
     */
    public function set(string $key, mixed $value, bool $overwrite = false): void;

    /**
     * Retrieves a session value.
     *
     * @param string $key     The key of the session value.
     * @param mixed  $default The default value to return if the key does not exist.
     *
     * @return mixed The session value if found, otherwise the default value.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Retrieves all session values.
     *
     * @return array All session values.
     */
    public function getAll(): array;

    /**
     * Removes a session value.
     *
     * @param string $key The key of the session value to remove.
     *
     * @return void
     */
    public function destroy(string $key): void;

    /**
     * Destroys all session data.
     *
     * @return void
     */
    public function destroyAll(): void;

    /**
     * Checks if the session is secure.
     *
     * @return bool True if the session is secure, otherwise false.
     */
    public function isSecure(): bool;

    /**
     * Sets a flash message.
     *
     * @param string $key   The key of the flash message.
     * @param mixed  $value The value of the flash message.
     *
     * @return void
     */
    public function setFlashMessage(string $key, mixed $value): void;

    /**
     * Retrieves a flash message and removes it from the session.
     *
     * @param string $key The key of the flash message.
     *
     * @return mixed The flash message value if found, otherwise null.
     */
    public function getFlashMessage(string $key): mixed;
}
