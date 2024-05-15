<?php

namespace Nova\Foundation;

use Nova\Container\Container;
use Nova\HTTP\IncomingRequest;
use Nova\HTTP\Response;

class Application extends Container
{
    /**
     * Application version
     *
     * @var string
     */
    protected string $version = '1.0.0';

    /**
     * Application's locale
     *
     * @var string
     */
    private string $locale;

    public function __construct()
    {
        $this->initialize();

        $config = $this->make('config');

        $this->locale = $config->get('config') ?? 'en';
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
     * Launch the application base on php environment
     *
     * @param IncomingRequest $request
     * @param Response        $response
     */
    public function launch(IncomingRequest $request, Response $response)
    {
        echo 'success';
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

    /**
     * Sets the locale of the application.
     *
     * This method sets the locale of the application to the specified value.
     * If the provided locale is not in the list of allowed locales configured in the application,
     * an InvalidArgumentException is thrown.
     *
     * @param string $locale The new locale to set.
     * @throws \InvalidArgumentException If the specified locale is not allowed.
     * @return void
     */
    public function setLocale(string $locale)
    {
        $allowedLocales = config('app.locales.list');

        if (!in_array($locale, $allowedLocales)) {
            throw new \InvalidArgumentException("Locale '{$locale}' is not allowed");
        }

        $this->locale = $locale;
    }

    /**
     * Gets the current locale of the application.
     *
     * @return string The current locale.
     */
    public function getLocale(): string
    {
        return config('app.locale') ?? $this->locale;
    }

    /**
     * Checks if the application is running in command line interface (CLI) mode.
     *
     * @return bool True if running in CLI mode; otherwise, false.
     */
    public function isCLI(): bool
    {
        return PHP_SAPI === 'cli';
    }
}
