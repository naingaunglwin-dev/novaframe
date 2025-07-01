<?php

namespace NovaFrame\Session\Drivers;

use Predis\Client;
use Predis\Response\Status;

/**
 * Class RedisSessionDriver
 *
 * Manages session data using a Redis backend. Supports both PhpRedis and Predis clients.
 *
 * Configuration options should include:
 * - redis.client: "phpredis" or "predis"
 * - redis.host
 * - redis.port
 * - redis.timeout
 * - redis.password (optional)
 * - redis.database (optional)
 * - prefix: session key prefix in Redis
 * - gc_maxlifetime: lifetime in seconds
 */
class RedisSessionDriver extends AbstractDriver
{
    /** @var \Redis|Client */
    private \Redis|Client $redis;

    /** @var string */
    private string $prefix;

    /**
     * RedisSessionDriver constructor.
     *
     * @param array $config Session configuration
     */
    public function __construct(protected array $config)
    {
        parent::__construct($config);
        $this->redis = $this->configure();
        $this->prefix = $this->config['prefix'] ?? 'novaframe_redis_session';
    }

    /**
     * Configure and return the Redis client instance.
     *
     * @return \Redis|Client
     */
    private function configure(): Client|\Redis
    {
        if ($this->config['redis']['client'] === 'phpredis' && !extension_loaded('redis')) {
            throw new \RuntimeException('Redis extension not loaded');
        }

        if ($this->config['redis']['client'] !== 'predis' && !class_exists('Predis\Client')) {
            throw new \RuntimeException('need to install predis/predis package');
        }

        if ($this->config['redis']['client'] === 'phpredis') {
            $redis = new \Redis();

            $redis->connect($this->config['redis']['host'], $this->config['redis']['port'], $this->config['redis']['timeout']);

            if (($password = $this->config['redis']['password']) !== null) {
                $redis->auth($password);
            }

            if (($database = $this->config['redis']['database']) !== null) {
                $redis->select($database);
            }
        } else {
            $redis = new Client([
                'host' => $this->config['redis']['host'],
                'port' => $this->config['redis']['port'],
                'timeout' => $this->config['redis']['timeout'],
                'password' => $this->config['redis']['password'],
                'database' => $this->config['redis']['database']
            ]);
        }

        return $redis;
    }

    /**
     * Start the session if not already started.
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

        $this->started = true;

        return true;
    }

    /**
     * Read session data from Redis.
     *
     * @param string $id Session ID
     * @return string Serialized session data
     */
    public function read(string $id): string
    {
        $data = $this->redis->get($this->prefix . $id);

        if (empty($data)) {
            $data = '';
        }

        return $data !== false ? serialize($data) : false;
    }

    /**
     * Write session data to Redis with expiration.
     *
     * @param string $id Session ID
     * @param mixed $data Serialized session data
     * @return bool
     */
    public function write(string $id, $data): bool
    {
        $status = $this->redis->setex($this->prefix . $id, $this->config['gc_maxlifetime'] ?? 1440, $data);

        if ($status instanceof Status) {
            return $status->getPayload() === 'OK';
        }

        return $status;
    }

    /**
     * Destroy a session by deleting its Redis key.
     *
     * @param string $id Session ID
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $this->redis->del($this->prefix . $id);
        $this->data = [];

        return true;
    }

    /**
     * Close the session and save data to Redis.
     *
     * @return bool
     */
    public function close(): bool
    {
        if ($this->started) {
            $this->write($this->id, serialize($this->data));

            if ($this->redis instanceof \Redis) {
                $this->redis->close();
            }

            $this->started = false;
        }

        return true;
    }

    /**
     * Regenerate session ID optionally destroying the old session.
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
     * Garbage collection for Redis sessions (no-op).
     *
     * @param int $lifetime
     * @return bool
     */
    public function gc(int $lifetime): bool
    {
        return true;
    }
}
