<?php

namespace Nova\Foundation;

use Nova\Container\Container;
use Nova\Facade\Event;
use Nova\Facade\Bootstrap;
use Nova\Route\RouteDispatcher;

class Application extends Container
{
    /**
     * Application version
     *
     * @var string
     */
    private const VERSION = '1.0.0';

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
        return Application::VERSION;
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
        inc(APP_PATH . 'Bootstrap/bootstrap.php');

        // Run bootstrapping before stage
        Bootstrap::run('before');

        inc(APP_PATH . 'Config/event.php');

        Event::emit("nova.before");

        if ($this->isCLI()) {

            Event::emit("nova.cli");

            $application = new \Nova\Console\Application(...$resource);

            $application->commandLoader();

            // Run bootstrapping before stage
            Bootstrap::run('after');

            return $application->run();
        }

        // Continue process if environment is not from cli
        Event::emit("nova.web");

        // Run bootstrapping after stage
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
     * Set or get the application's locale.
     *
     * This allows you to either set the application's locale or retrieve the current locale.
     *
     * - If a locale is passed as an argument, the method will set it as the application's locale
     *   (if it is in the list of allowed locales) and return the updated locale.
     * - If no argument is passed, the method will simply return the currently set locale.
     *
     * @param string|null $locale The locale to set, or null to get the current locale.
     * @return string The current or updated locale.
     * @throws \InvalidArgumentException If the provided locale is not allowed.
     */
    public function locale(string $locale = null): string
    {
        if ($locale !== null) {
            $allowedLocales = config('app.locales.list');

            if (!in_array($locale, $allowedLocales)) {
                throw new \InvalidArgumentException("Locale '{$locale}' is not allowed.");
            }

            $this->locale = $locale;
        }

        return $this->locale;
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
