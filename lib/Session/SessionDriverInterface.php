<?php

namespace NovaFrame\Session;

interface SessionDriverInterface
{
    /**
     * Starts the session and initializes internal data storage.
     *
     * @return bool True on success, false on failure.
     */
    public function start(): bool;

    /**
     * Reads the session data for a given session ID.
     *
     * @param string $id The session ID to read data for.
     * @return string The serialized session data.
     */
    public function read(string $id): string;

    /**
     * Writes session data for a given session ID.
     *
     * @param string $id The session ID.
     * @param string $data The data to store (typically serialized).
     * @return bool True if the data was written successfully.
     */
    public function write(string $id, $data): bool;

    /**
     * Destroys the session data for the given session ID.
     *
     * @param string $id The session ID to destroy.
     * @return bool
     */
    public function destroy(string $id): bool;

    /**
     * Closes the session and performs any cleanup tasks.
     *
     * @return bool
     */
    public function close(): bool;

    /**
     * Regenerates the session ID.
     *
     * @param bool $deleteOldSession Whether to delete the old session data.
     * @return string
     */
    public function regenerateId(bool $deleteOldSession = false): string;

    /**
     * Sets the session ID manually.
     *
     * @param string $id The session ID to set.
     * @return void
     */
    public function setId(string $id): void;

    /**
     * Gets the current session ID.
     *
     * @return string|null The current session ID, or null if not set.
     */
    public function getId(): ?string;

    /**
     * Sets the session name (typically the cookie name).
     *
     * @param string $name The name of the session.
     * @return void
     */
    public function setName(string $name);

    /**
     * Gets the current session name.
     *
     * @return string|null The current session name, or null if not set.
     */
    public function getName(): ?string;

    /**
     * Performs garbage collection for expired sessions.
     *
     * @param int $lifetime The session lifetime in seconds.
     * @return mixed
     */
    public function gc(int $lifetime);
}
