<?php

namespace NovaFrame\Session\Drivers;

use NovaFrame\Session\Exceptions\InvalidSessionIdFormat;
use NovaFrame\Session\Exceptions\SessionAlreadyStarted;
use NovaFrame\Session\SessionDriverInterface;
use Ramsey\Uuid\Uuid;

abstract class AbstractDriver implements SessionDriverInterface
{
    /**
     * The session ID.
     *
     * @var string|null
     */
    protected ?string $id = null;

    /**
     * The name of the session cookie.
     *
     * @var string
     */
    protected string $name = 'novaframe_session';

    /**
     * Indicates whether the session has started.
     *
     * @var bool
     */
    protected bool $started = false;

    /**
     * The session data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * AbstractDriver constructor.
     *
     * @param array $config Session configuration array
     */
    public function __construct(protected array $config)
    {
        if (isset($this->config['name'])) {
            $this->name = $this->config['name'];
        }
    }

    /**
     * Set the session ID.
     *
     * @param string $id UUIDv4-formatted session ID
     *
     * @throws SessionAlreadyStarted If the session is already started
     * @throws InvalidSessionIdFormat If the given ID is not a valid UUIDv4
     */
    public function setId(string $id): void
    {
        if ($this->isStarted()) {
            throw new SessionAlreadyStarted();
        }

        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id)) {
            throw new InvalidSessionIdFormat($id);
        }

        $this->id = $id;
    }

    /**
     * Get the current session ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?? '';
    }

    /**
     * Set the session name (cookie name).
     *
     * @param string $name
     *
     * @throws SessionAlreadyStarted If the session is already started
     */
    public function setName(string $name): void
    {
        if ($this->isStarted()) {
            throw new SessionAlreadyStarted();
        }

        $this->name = $name;
    }

    /**
     * Get the current session name (cookie name).
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Check if the session has already started.
     *
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * Generate a new UUIDv4 session ID.
     *
     * @return string
     */
    protected function generateId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
