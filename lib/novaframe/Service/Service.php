<?php

namespace Nova\Service;

use Nova\Container\Container;

class Service
{
    /**
     * List of services
     *
     * @var array
     */
    private array $list = [
        'config'    => \Nova\Service\Config\Config::class,
        'dotenv'    => \Nova\Service\Dotenv\Dotenv::class,
        'language'  => \Nova\Service\Language\Language::class,
        'log'       => \Nova\Service\Logger\Log::class,
        'session'   => \Nova\Service\Session\Session::class,
        'bootstrap' => \Nova\Service\Bootstrap\Bootstrap::class,
        'cookie'    => \Nova\Service\Cookie\Cookie::class,
        'stash'     => \Nova\Service\Stash\Stash::class,
    ];

    private Container $container;

    /**
     * Service constructor.
     *
     * @param Container|null $container An optional container instance.
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?? new Container();

        foreach ($this->list as $service => $class) {
            $this->container->add("Service[$service]", $class);
        }
    }

    /**
     * Retrieve a service instance.
     *
     * @param string $service    The name of the service to retrieve.
     * @param mixed  ...$parameters Optional parameters to pass to the service constructor.
     *
     * @return mixed The service instance or null if the service does not exist.
     */
    public function get(string $service, mixed ...$parameters): mixed
    {
        if (!$this->serviceExists($service)) {
            return null;
        }

        return $this->container->make("Service[$service]", ...$parameters);
    }

    /**
     * Check if a service exists in the list.
     *
     * @param string $service The name of the service to check.
     *
     * @return bool True if the service exists, false otherwise.
     */
    private function serviceExists(string $service): bool
    {
        return isset($this->list[$service]);
    }
}
