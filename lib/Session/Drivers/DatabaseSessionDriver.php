<?php

namespace NovaFrame\Session\Drivers;

use Nette\Database\Table\Selection;
use NovaFrame\Database\Database;

/**
 * Class DatabaseSessionDriver
 *
 * Handles session storage using a database table via Nette Database.
 * Provides support for garbage collection and session regeneration.
 *
 * Expected table schema:
 * - session_id (string, primary key)
 * - payload (text or blob)
 * - last_activity (int, timestamp)
 */
class DatabaseSessionDriver extends AbstractDriver
{
    /**
     * The name of the database table used for storing sessions.
     *
     * @var string
     */
    private string $table;

    /**
     * The Nette Database table selection instance.
     *
     * @var Selection
     */
    private Selection $db;

    /**
     * DatabaseSessionDriver constructor.
     *
     * @param array $config Configuration options:
     *                      - table: string (table name)
     *                      - gc_probability: int
     *                      - gc_divisor: int
     *                      - gc_maxlifetime: int
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->table = $config['table'];
        $this->db = Database::table($this->table);
    }

    /**
     * Start the session. Loads session data from the database.
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

        $data = $this->db->select('*')->where('session_id', $this->id)->fetch()->toArray();

        if (!empty($data)) {
            $this->data = unserialize($data['payload']);
        }

        $this->started = true;

        $probability = $this->config['gc_probability'] ?? 1;
        $divisor = $this->config['gc_divisor'] ?? 100;

        if ($divisor > 0 && mt_rand(1, $divisor) <= $probability) {
            $this->gc($this->config['gc_maxlifetime'] ?? 1440);
        }

        return true;
    }

    /**
     * Read the session data for the given session ID.
     *
     * @param string $id
     * @return string Serialized session data
     */
    public function read(string $id): string
    {
        $data = $this->db->select('*')->where('session_id', $id)->fetch()->toArray();

        if (!empty($data)) {
            $this->data = unserialize($data['payload']);
        }

        return serialize($this->data);
    }

    /**
     * Write session data to the database.
     * Inserts or updates the session row.
     *
     * @param string $id
     * @param mixed $data Serialized session data
     * @return bool
     */
    public function write(string $id, $data): bool
    {
        $session = $this->db->select('*')->where('session_id', $id)->fetch()->toArray();

        if (empty($session)) {
            return (bool)$this->db->insert([
                'session_id' => $id,
                'payload' => serialize($data),
                'last_activity' => time(),
            ]);
        }

        return $this->db->where('session_id', $id)->update([
            'payload' => serialize($data),
        ]);
    }

    /**
     * Destroy the session with the given ID.
     *
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        return (bool)$this->db->where('session_id', $id)->delete();
    }

    /**
     * Close the session and persist the latest session data.
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
     * Regenerate session ID.
     * Optionally deletes the old session row.
     *
     * @param bool $deleteOldSession
     * @return string New session ID
     */
    public function regenerateId(bool $deleteOldSession = false): string
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $oldId = $this->id;
        $this->id = $this->generateId();

        if ($deleteOldSession) {
            $this->destroy($oldId);
        } else {
            $this->write($this->id, serialize($this->data));
        }

        return true;
    }

    /**
     * Garbage collect old sessions older than the given lifetime.
     *
     * @param int $lifetime Seconds
     * @return bool
     */
    public function gc(int $lifetime): bool
    {
        $this->db->where('last_activity < ?', time() - $lifetime)->delete();

        return true;
    }
}
