<?php

namespace NovaFrame;

use NovaFrame\Container\Container;
use NovaFrame\Exception\ExceptionHandler;
use NovaFrame\Exception\HandlerInterface;
use NovaFrame\Helpers\Path\Path;

class Bootstrap
{
    /**
     * List of exception handlers to register
     *
     * @var HandlerInterface[]
     */
    private array $exceptions = [];

    /**
     * Autoload configuration containing 'file' and 'strict' keys
     *
     * @var array
     */
    private array $autoload = [];

    /**
     * Callbacks to run before bootstrapping the application
     *
     * @var callable[]
     */
    private array $beforeCallback = [];

    /**
     * Optional fallback exception view path
     *
     * @var string|null
     */
    private ?string $fallbackExceptionView = null;

    /**
     * Set exception handlers to register.
     *
     * @param HandlerInterface[] $exceptions Array of exception handler instances.
     * @return $this
     */
    public function exception(array $exceptions): Bootstrap
    {
        $this->exceptions = $exceptions;

        return $this;
    }

    /**
     * Configure autoload files.
     *
     * @param string|string[] $file Single or multiple files or directories to autoload.
     * @param bool $strict Whether to throw an exception if files are missing. Default false.
     * @return $this
     */
    public function autoload(string|array $file, bool $strict = false): Bootstrap
    {
        $this->autoload = [
            'strict' => $strict,
            'file' => is_string($file) ? [$file] : $file,
        ];

        return $this;
    }

    /**
     * Register a callback to run before bootstrapping.
     *
     * @param callable $callback Callback function or invokable class.
     * @return $this
     */
    public function before(callable $callback): Bootstrap
    {
        $this->beforeCallback[] = $callback;

        return $this;
    }

    /**
     * Set fallback exception view to render when no other handlers apply.
     *
     * @param string $view View file path or name.
     * @return $this
     */
    public function fallbackExceptionView(string $view): Bootstrap
    {
        $this->fallbackExceptionView = $view;

        return $this;
    }

    /**
     * Finalize the bootstrap process and return the Kernel instance.
     *
     * This runs before callbacks, loads configured files,
     * and registers exception handlers.
     *
     * @return Kernel The initialized application kernel.
     */
    public function export(): Kernel
    {
        $this->run();

        $kernel = Kernel::getInstance($this->fallbackExceptionView);

        //exception
        if (!empty($this->exceptions)) {
            foreach ($this->exceptions as $exception) {
                $kernel->make(ExceptionHandler::class)->register($exception);
            }
        }

        return $kernel;
    }

    /**
     * Run before callbacks and autoload files.
     *
     * @throws \RuntimeException If strict autoloading is enabled and file not found.
     */
    private function run(): void
    {
        // before
        if (!empty($this->beforeCallback)) {
            foreach ($this->beforeCallback as $callback) {
                (new Container())->get($callback);
            }
        }

        // autoloading
        if (!empty($this->autoload)) {

            $strict = $this->autoload['strict'];
            $files = array_merge(...array_map(
                fn($file) => is_array($file) ? $file : [$file],
                $this->autoload['file']
            ));

            foreach ($files as $file) {
                $file = Path::join(DIR_ROOT, $file);
                if (is_file($file)) {
                    require $file;
                } elseif ($strict) {
                    throw new \RuntimeException("File '{$file}' is not found.");
                }
            }
        }
    }
}
