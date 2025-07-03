<?php

namespace NovaFrame;

use NovaFrame\Console\Application;
use NovaFrame\Console\CommandLoader;
use NovaFrame\Container\Container;
use NovaFrame\Exception\ExceptionHandler;
use NovaFrame\Facade\Config;
use NovaFrame\Facade\Session;
use NovaFrame\Http\Request;
use NovaFrame\Http\Response;
use NovaFrame\Route\RouteCollection;
use NovaFrame\Route\RouteDispatcher;
use Symfony\Component\Console\Input\ArgvInput;

class Kernel extends Container
{
    const NAME = 'NovaFrame';

    const VERSION = '1.0.0';

    private string $locale;

    public function __construct(
        private ?string $fallbackExceptionView = null
    )
    {
        parent::__construct();

        $this->initialize();
    }

    private function initialize(): void
    {
        $this->registerExceptionHandler();

        date_default_timezone_set(Config::get('app.timezone', 'UTC'));

        $this->singleton('routes', RouteCollection::class);

        $this->singleton(Response::class, Response::class);

        $this->locale = Config::get('app.locale', 'en');
    }

    private function registerExceptionHandler(): void
    {
        $this->singleton(ExceptionHandler::class, ExceptionHandler::class);

        $this->make(ExceptionHandler::class, ['fallback' => $this->fallbackExceptionView])->initialize();
    }

    /**
     * Get or set the application locale.
     *
     * @param string|null $locale
     * @return string
     * @throws \InvalidArgumentException If locale is unsupported.
     */
    public function locale(?string $locale = null): string
    {
        if (!empty($locale)) {
            if (!in_array($locale, Config::get('app.locales', []))) {
                throw new \InvalidArgumentException($locale . ' is not a supported locale.');
            }

            $this->locale = $locale;
        }

        return $this->locale;
    }

    public function name(): string
    {
        return self::NAME;
    }

    public function version(): string
    {
        return self::VERSION;
    }

    public function environment(): string
    {
        return Config::get('app.env', 'production');
    }

    /**
     * Boot the application.
     *
     * @param Request|ArgvInput|Maintenance $arg
     * @return mixed
     */
    public function boot(Request|ArgvInput|Maintenance $arg)
    {
        // Start output buffering to suppress any output before exception views are rendered,
        // ensuring a clean output in case of errors.
        ob_start();

        $response = new Response();

        Session::start();

        require DIR_CONFIG . 'event.php';

        if ($arg instanceof Maintenance) {
            return $this->terminate($arg->run($response), $response);
        }

        if (RuntimeEnv::envIs('cli')) {
            return (new Application(
                new CommandLoader(DIR_APP . 'Commands')
            ))->run($arg);
        }

        require DIR_APP . 'Routes' . DS . 'app.php';

        $respond = (new RouteDispatcher(
            $this, $this->make('routes')
        ))->dispatch($arg, $response);

        return $this->terminate($respond, $response);
    }

    /**
     * Terminate the application, send response if needed.
     *
     * @param mixed $respond
     * @param Response $response
     * @return int|mixed
     */
    public function terminate($respond, Response $response)
    {
        Session::save();

        if ($respond instanceof Response) {
            $respond->send();
            $respond->clean();
            return 0;
        }

        if (is_string($respond)) {
            $response->setContent($respond)->send();
            $response->clean();
            return 0;
        }

        $response->clean(); // make sure restore any values to original

        return $respond;
    }
}
