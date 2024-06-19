<?php

namespace Nova\Foundation;

use Nova\Container\Container;
use Nova\Event\Event;
use Nova\Exception\HandlerInterface;
use Nova\Facade\Bootstrap;
use Nova\Route\RouteDispatcher;

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
     * Boot the application base on php environment
     *
     * @param mixed $resource Resources to pass to web or cli application
     *
     * @return mixed
     */
    public function boot(mixed ...$resource): mixed
    {
        required(APP_PATH . 'Bootstrap/bootstrap.php');

        // Run bootstrapping before stage
        Bootstrap::run('before');

        required(APP_PATH . 'Config/event.php');

        Event::trigger('NovaFrame.system.before');

        if ($this->isCLI()) {

            Event::trigger('nova.cli');

            $application = new \Nova\Console\Application(...$resource);

            $application->commandLoader();

            // Run bootstrapping before stage
            Bootstrap::run('after');

            return $application->run();
        }

        // Continue process if environment is not from cli
        Event::trigger('nova.web');

        // Run bootstrapping before stage
        Bootstrap::run('after');

        return RouteDispatcher::getInstance()->dispatch(...$resource);
    }

    /**
     * Initialize the application by pre-loading essential services.
     *
     * This method is called during the construction of the Application class
     * to pre-load essential services into the container.
     *
     * @return void
     */
    private function initialize(): void
    {
        $this->setupHandler();

        $this->locale = config('app.locale');
    }

    /**
     * Setup Framework Handler
     *
     * @return void
     */
    private function setupHandler(): void
    {
        $abstract = \Nova\Exception\HandlerInterface::class;

        $this->singleton(
            $abstract,
            \Nova\Exception\Handler::class
        );

        $this->make($abstract)->set();
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
