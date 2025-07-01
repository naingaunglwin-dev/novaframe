<?php

namespace NovaFrame\Session\Drivers;

use NovaFrame\Helpers\Path\Path;

class FileSessionDriver extends AbstractDriver
{
    /**
     * Directory where session files are stored.
     *
     * @var string
     */
    private string $path;

    /**
     * Fallback temporary directory path for session storage.
     *
     * @var string
     */
    private string $tmpdir;

    /**
     * FileSessionDriver constructor.
     *
     * @param array $config Configuration for session storage:
     *                      - write_path: string (optional)
     *                      - gc_probability: int (optional)
     *                      - gc_divisor: int (optional)
     *                      - gc_maxlifetime: int (optional)
     */
    public function __construct(array $config)
    {
        $this->tmpdir = Path::join(sys_get_temp_dir(), app()->name(), 'session');

        parent::__construct($config);
        $this->path = $config['write_path'] ?? $this->tmpdir;

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    /**
     * Starts the session, loads session ID and triggers garbage collection randomly.
     *
     * @return bool
     */
    public function start(): bool
    {
        if ($this->isStarted()) {
            return true;
        }

        if (empty($this->id)) {
            $this->id = $this->generateId();
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
     * Reads session data from a file.
     *
     * @param string $id
     * @return string|false Returns data or false if not found
     */
    public function read(string $id): string
    {
        $file = Path::join($this->path, $id);

        if (!file_exists($file) || !is_readable($file)) {
            return false;
        }

        return file_get_contents($file);
    }

    /**
     * Writes session data to a file using the session ID as the filename.
     *
     * @param string $id
     * @param mixed $data Serialized session data
     * @return bool
     */
    public function write(string $id, $data): bool
    {
        $file = Path::join($this->path, $id);

        return file_put_contents($file, $data, LOCK_EX) !== false;
    }

    /**
     * Deletes the session file for the given session ID.
     *
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $file = Path::join($this->path, $id);

        if (file_exists($file)) {
            return unlink($file);
        }

        return true;
    }

    /**
     * Closes the session and persists the current data.
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
     * Generates a new session ID and optionally deletes the old session.
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

        return $this->id;
    }

    /**
     * Garbage collector: deletes expired session files.
     *
     * @param int $lifetime Session max lifetime in seconds
     * @return bool
     */
    public function gc(int $lifetime): bool
    {
        $files = glob(Path::join($this->path, '*'));

        foreach ($files as $file) {
            if (filemtime($file) + $lifetime < time()) {
                unlink($file);
            }
        }

        return true;
    }
}