<?php

namespace NovaFrame\Session;

use NovaFrame\Encryption\Encryption;
use NovaFrame\Session\Drivers\CookieSessionDriver;
use NovaFrame\Session\Drivers\DatabaseSessionDriver;
use NovaFrame\Session\Drivers\FileSessionDriver;
use NovaFrame\Session\Drivers\NativeSessionDriver;
use NovaFrame\Session\Drivers\RedisSessionDriver;
use Ramsey\Uuid\Uuid;

class Session
{
    /**
     * Whether the session is started
     *
     * @var bool
     */
    private bool $started = false;

    /**
     * Session data
     *
     * @var array
     */
    private array $data = [];

    /**
     * Flash data
     *
     * @var array
     */
    private array $flash = [];

    /**
     * Configuration for the session
     *
     * @var array
     */
    private array $config;

    /**
     * Session cookie name
     *
     * @var string
     */
    private string $name;

    /**
     * Cached CSRF token
     *
     * @var string
     */
    private string $csrfToken;

    /**
     * Whether session data should be encrypted
     *
     * @var bool
     */
    private bool $shouldEncrypt;

    /**
     * Session constructor.
     *
     * @param SessionDriverInterface|null $driver Optional session driver
     */
    public function __construct(private ?SessionDriverInterface $driver = null)
    {
        $this->config = config('session', []);
        $this->name = $this->config['name'] ?? 'novaframe_session';
        $this->shouldEncrypt = $this->config['encrypt'] ?? true;
        $this->driver ??= $this->createDriver();
    }

    /**
     * Create and return a session driver instance.
     *
     * @param string|null $driver Driver name or null to use config
     * @return SessionDriverInterface
     */
    public function createDriver(?string $driver = null): SessionDriverInterface
    {
        $driver ??= $this->config['driver'];

        return match (strtolower($driver)) {
            'native' => new NativeSessionDriver($this->config),
            'file' => new FileSessionDriver($this->config),
            'redis' => new RedisSessionDriver($this->config),
            'database' => new DatabaseSessionDriver($this->config),
            'cookie' => new CookieSessionDriver($this->config),
            default => throw new \InvalidArgumentException('Unsupported driver: ' . $driver),
        };
    }

    /**
     * Start the session.
     *
     * @return bool
     */
    public function start(): bool
    {
        if ($this->started) {
            return true;
        }

        $id = $_COOKIE[$this->name] ?? null;

        if ($id) {
            $this->driver->setId($id);
        }

        $result = $this->driver->start();

        if ($result) {
            $this->started = true;
            $this->fetch();
        }

        if (!$id) {
            $this->saveIdInCookie();
        }

        return $result;
    }

    /**
     * Get a session value.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->ensureStarted();

        return isset($this->data[$key])
            ? (
                $this->shouldEncrypt
                    ? Encryption::decrypt($this->data[$key])
                    : $this->data[$key]
            )
            : $default;
    }

    /**
     * Set a session value.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->ensureStarted();

        $this->data[$key] = $this->shouldEncrypt ? Encryption::encrypt($value) : $value;
    }

    /**
     * Check if a session key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $this->ensureStarted();

        return isset($this->data[$key]);
    }

    /**
     * Remove a session key.
     *
     * @param string $key
     */
    public function remove(string $key): void
    {
        $this->ensureStarted();

        unset($this->data[$key]);
    }

    /**
     * Forget a session and flash key.
     *
     * @param string $key
     */
    public function forget(string $key): void
    {
        $this->ensureStarted();

        unset($this->data[$key], $this->flash[$key]);
    }

    /**
     * Clear all session data.
     */
    public function clean(): void
    {
        $this->ensureStarted();

        $this->data  = [];
        $this->flash = [];
    }

    /**
     * Restart session and regenerate ID.
     */
    public function restart(): void
    {
        $this->driver->start();
        $this->regenerateId(true);
        $this->clean();
    }

    /**
     * Regenerate session id
     *
     * @param bool $deleteOldSession
     * @return void
     */
    public function regenerateId(bool $deleteOldSession = false): void
    {
        $this->driver->regenerateId($deleteOldSession);
    }

    /**
     * Get all session data.
     *
     * @return array
     */
    public function all(): array
    {
        $this->ensureStarted();

        return array_map(fn($value) => $this->shouldEncrypt ? Encryption::decrypt($value) : $value, $this->data);
    }

    /**
     * Flash a value for next request only.
     *
     * @param string $key
     * @param mixed $value
     */
    public function flash(string $key, $value): void
    {
        $this->ensureStarted();

        $this->flash[$key] = [
            'value' => $this->shouldEncrypt ? Encryption::encrypt($value) : $value,
            'new' => true
        ];
    }

    /**
     * Get a flash value.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getFlash(string $key, $default = null)
    {
        $this->ensureStarted();

        return isset($this->flash[$key])
            ? (
                $this->shouldEncrypt
                    ? Encryption::decrypt($this->flash[$key]['value'])
                    : $this->flash[$key]['value']
            )
            : $default;
    }

    /**
     * Save session to driver.
     */
    public function save(): void
    {
        $data = [
            'data' => $this->data,
            'flash' => $this->flash
        ];

        if ($this->started) {
            $this->driver->write($this->id(), serialize($data));
        }
    }

    /**
     * Get current session ID.
     *
     * @return string|null
     */
    public function id()
    {
        return $this->driver->getId();
    }

    /**
     * Get session name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generate or return a CSRF token.
     *
     * @return string
     */
    public function getCsrfToken(): string
    {
        $this->ensureStarted();

        if (!isset($this->csrfToken)) {
            $token = $this->get('csrf_token');

            if (!$token) {
                $token = Uuid::uuid4()->toString();
                $this->set('csrf_token', $token);
            }

            $this->csrfToken = $token;
        }

        return $this->csrfToken;
    }

    /**
     * Validate CSRF token.
     *
     * @param string $token
     * @return bool
     */
    public function validateCsrfToken(string $token): bool
    {
        $this->ensureStarted();

        return hash_equals($this->getCsrfToken(), $token);
    }

    /**
     * Destroy current session
     *
     * @return bool
     */
    public function destroy(): bool
    {
        return $this->driver->destroy($this->id());
    }

    /**
     * Ensure the session is started.
     *
     * @internal
     * @return void
     */
    private function ensureStarted(): void
    {
        if ($this->started) {
            return;
        }

        $this->start();
    }

    /**
     * Fetch session and flash data from driver.
     *
     * @internal
     */
    private function fetch(): void
    {
        $data = $this->driver->read($this->id());

        if (!$data) {
            return;
        }

        $data = unserialize($data);

        if (is_array($data) && !empty($data)) {
            $this->data  = $data['data'];
            $this->flash = $data['flash'];

            foreach ($this->flash as $key => $value) {
                if (!$value['new']) {
                    unset($this->flash[$key]);
                } else {
                    $this->flash[$key]['new'] = false;
                }
            }
        }
    }

    /**
     * Save session ID into cookie.
     *
     * @internal
     */
    private function saveIdInCookie(): void
    {
        setcookie(
            $this->name,
            $this->driver->getId(),
            [
                'expires' => time() + ($this->config['expire'] ?? 604800),
                'path' => $this->config['session_path'] ?? '/',
                'domain' => $this->config['domain'] ?? '',
                'secure' => $this->config['secure'] ?? false,
                'httponly' => $this->config['httponly'] ?? false,
                'samesite' => $this->config['samesite'] ?? 'Lax',
            ]
        );
    }
}
