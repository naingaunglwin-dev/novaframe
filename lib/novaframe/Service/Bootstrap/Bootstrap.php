<?php

namespace Nova\Service\Bootstrap;

class Bootstrap
{
    private static array $process = [];

    /**
     * Register a process to run before the application launches.
     *
     * @param callable $process
     * @return self
     */
    public function before(callable $process): Bootstrap
    {
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
        $this->save('after', $process);

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
     * Register a process to run with other stages.
     *
     * @param callable $process
     * @return Bootstrap
     */
    public function with(callable $process): Bootstrap
    {
        $this->save('with', $process);

        return $this;
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
                    $process();
                }
            }
        }

        if ($stage !== 'with' && !isset(self::$process['with'])) {
            $this->run('with');
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
    public function flattenFilesArray(array $files): array
    {
        $result = [];
        array_walk_recursive($files, function ($a) use (&$result) {
            $result[] = $a;
        });
        return $result;
    }
}
