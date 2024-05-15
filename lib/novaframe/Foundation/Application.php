<?php

namespace Nova\Foundation;

use Nova\Container\Container;

class Application extends Container
{
    /**
     * Application version
     *
     * @var string
     */
    protected string $version = '1.0.0';

    public function __construct()
    {
        $this->initialize();
    }

    /**
     * The version of the application
     *
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * Initialize the application by pre-loading essential services.
     *
     * This method is called during the construction of the Application class
     * to pre-load essential services into the container.
     *
     * @return void
     */
    private function initialize()
    {
        $this->add('dotenv', \Nova\Dotenv\Dotenv::class);
        $this->add('config', \Nova\Config\Config::class);
    }
}
