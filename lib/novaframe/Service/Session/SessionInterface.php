<?php

namespace Nova\Service\Session;

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
     * Sets a flash message to be available for the next request.
     *
     * @param string $key   The key under which the flash message will be stored.
     * @param mixed  $value The flash message value.
     *
     * @return void
     */
    public function flash(string $key, $value): void;

    /**
     * Push a value onto a session array.
     *
     * @param string $key   The session key where the value will be pushed.
     * @param mixed  $value The value to be pushed into the array.
     *
     * @return void
     *
     * @throws \BadMethodCallException If the value associated with the key is not an array.
     */
    public function push(string $key, mixed $value): void;

    /**
     * Checks if a session key exists
     *
     * @param string $key The session key to check.
     *
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool;
}
