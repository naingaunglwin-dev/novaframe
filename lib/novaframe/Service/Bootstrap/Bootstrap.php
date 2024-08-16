<?php

namespace Nova\Service\Bootstrap;

class Bootstrap
{
    /**
     * Bootstrapping processes
     *
     * @var array
     */
    private static array $process = [];

    /**
     * Current Stage (before or after)
     *
     * @var string
     */
    private static string $current;

    /**
     * Register a process to run before the application launches.
     *
     * @param callable $process
     * @return self
     */
    public function before(callable $process): Bootstrap
    {
        self::$current = "before";

        $this->save('before', $process);

        return $this;
    }

    /**
     * Register a process to run after the application launches.
     *
     * @param callable $process
     * @return Bootstrap
     */
    public function after(callable $process): Bootstrap
    {
        self::$current = "after";

        $this->save('after', $process);

        return $this;
    }

    /**
     * Register a process to run for web requests.
     *
     * This method allows you to register a callback that will be executed specifically
     * for web-based requests. This can be useful for initializing resources or
     * performing tasks that are only relevant in a web context.
     *
     * @param callable $callback The callback function to execute for web requests.
     * @return Bootstrap Returns the instance of the Bootstrap class for method chaining.
     */
    public function web(callable $callback): Bootstrap
    {
        $this->save(empty(self::$current) ? 'web' : self::$current . '.web', $callback);

        return $this;
    }

    /**
     * Register a process to run for CLI requests.
     *
     * This method allows you to register a callback that will be executed specifically
     * for command-line interface (CLI) requests. This can be useful for initializing
     * resources or performing tasks that are only relevant in a CLI context.
     *
     * @param callable $callback The callback function to execute for CLI requests.
     * @return self Returns the instance of the Bootstrap class for method chaining.
     */
    public function cli(callable $callback): Bootstrap
    {
        $this->save(empty(self::$current) ? 'cli' : self::$current . '.cli', $callback);

        return $this;
    }

    /**
     * Autoload files.
     *
     * @param array|string $files
     * @return Bootstrap
     */
    public function autoload(array|string $files): Bootstrap
    {
        $this->save('autoload', $files);

        return $this;
    }

    /**
     * Get processes by stage.
     *
     * @param string $stage
     * @return array|null
     */
    public function getProcess(string $stage): ?array
    {
        return self::$process[$stage] ?? null;
    }

    /**
     * Get all registered processes.
     *
     * @return array
     */
    public function getAllProcess(): array
    {
        return self::$process;
    }

    /**
     * Save a process to a specific stage.
     *
     * @param string $stage
     * @param mixed $process
     * @return void
     */
    private function save(string $stage, mixed $process): void
    {
        self::$process[$stage][] = $process;
    }

    /**
     * Run processes for a specific stage.
     *
     * @param string $stage
     * @return Bootstrap
     */
    public function run(string $stage): Bootstrap
    {
        $stage = strtolower($stage);

        if (!empty($processes = $this->getProcess($stage))) {

            foreach ($processes as $process) {
                if (is_callable($process)) {
                    di()->callback($process);
                }
            }
        }

        if (php_sapi_name() === 'cli' && $stage !== 'cli') {
            if (isset(self::$process[$stage . '.cli'])) {
                $this->run('cli');
            }
        } else {
            if (isset(self::$process[$stage . '.web']) && $stage !== 'web' && php_sapi_name() !== 'cli') {
                $this->run($stage . '.web');
            }
        }

        if (!empty($autoload = $this->getProcess('autoload'))) {
            $files = $this->flattenFilesArray($autoload);

            foreach ($files as $file) {
                include $file;
            }
        }

        return $this;
    }

    /**
     * Recursively flatten an array of file paths.
     *
     * @param array $files
     * @return array
     */
    private function flattenFilesArray(array $files): array
    {
        $result = [];
        array_walk_recursive($files, function ($a) use (&$result) {
            $result[] = $a;
        });
        return $result;
    }
}
