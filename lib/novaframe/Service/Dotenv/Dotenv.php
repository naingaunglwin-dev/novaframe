<?php

namespace Nova\Service\Dotenv;

use Nova\Exception\Exceptions\FileException;
use Nova\Exception\Exceptions\FormatException;

class Dotenv
{
    /**
     * Env File
     *
     * @var string
     */
    private string $file;

    /**
     * Local env data
     *
     * @var array
     */
    private array $local = [];

    /**
     * $_ENV
     *
     * @var array
     */
    private array $global = [];

    /**
     * Env group
     *
     * @var array
     */
    private array $group = [];

    /**
     * Condition for `.env` file load process is finished or not
     *
     * @var bool
     */
    private bool $isLoaded = false;

    /**
     * Environment keys stored in $_SERVER, $_ENV
     *
     * @var array
     */
    private array $envKeys = [];

    /**
     * Constructor for the Dotenv class.
     *
     */
    public function __construct()
    {
        $this->file = ROOT_PATH . '/.env';

        $this->load();

        $this->updateGlobalEnv();
    }

    /**
     * Retrieve the value of the specified environment variable by key.
     *
     * @param string $key The key of the environment variable.
     * @return mixed The value of the specified environment variable if it exists, otherwise null.
     */
    public function get(string $key): mixed
    {
        if ($this->isLoaded === false) {
            $this->load();
        }

        if (isset($this->global[$key])) {
            return $this->global[$key];
        }

        return null;
    }

    /**
     * Retrieve environment variables belonging to a specific group.
     *
     * This method retrieves the environment variables that belong to the specified group.
     *
     * @param string $group The name of the group to retrieve environment variables for.
     * @return array|null An array containing the environment variables belonging to the specified group, or null if the group does not exist.
     * @throws \BadMethodCallException If the 'load()' method has not been invoked before calling this method.
     */
    public function getInGroup(string $group): ?array
    {
        if ($this->isLoaded === false) {
            throw new \BadMethodCallException(sprintf("The %s::load must be invoked before calling the others method.", $this::class));
        }

        return $this->group[$group] ?? null;
    }

    /**
     * Set a new environment variable or update an existing one.
     *
     * @param string $key The key (variable name) to set or update.
     * @param mixed $value The value to assign to the variable.
     * @param bool|null $overwrite If true, the variable will be updated if it exists, otherwise it won't be updated.
     *
     * @return void
     */
    public function set(string $key, mixed $value, bool $overwrite = null): void
    {
        if ($this->isLoaded === false) {
            throw new \BadMethodCallException(sprintf("The %s::load must be invoked before calling the 'set()' method.", $this::class));
        }

        if ($overwrite === null) {
            $overwrite = false;
        }

        $this->store([$key => $value], $overwrite);

        $this->updateGlobalEnv();
    }

    /**
     * Restart the environment variables with given defaults.
     *
     * @param array|null $default An array of default variables.
     * @return void
     */
    public function restart(array $default = null): void
    {
        $this->local = [];

        $this->updateGlobalEnv($default);

        $this->load();

        $this->updateGlobalEnv($default);
    }

    /**
     * Load the variables from the .env file.
     *
     * @throws FormatException If a variable name does not match the allowed pattern.
     * @return Dotenv
     */
    public function load(): Dotenv
    {
        if ($this->isLoaded) {
            return $this;
        }

        $file = $this->file;

        if (!file_exists($file)) {
            copy(ROOT_PATH . '/.env.example', ROOT_PATH . '/.env');
        }

        if (!is_file($file)) {
            throw FileException::pathNotFound($file);
        }

        $array = file($file);
        $contents = [];

        foreach ($array as $content) {

            $content = trim($content);

            if (str_contains($content, '#')) {
                continue;
            }

            $e = explode("=", $content, 2);

            if (count($e) === 2) {
                $key   = trim($e[0]);
                $value = trim($e[1]);

                $key   = $this->removeQuotes($key);
                $value = $this->removeQuotes($value);

                $contents[$key] = $value;

                preg_match('/^[A-Za-z]+(?=_)/', $key, $matches);

                if ($matches) {
                    $this->group[$matches[0]][] = $key;
                }
            }
        }

        $contents['REQUEST_SCHEME'] = $_SERVER['REQUEST_SCHEME'] ?? 'http';

        $this->store($contents);

        $this->isLoaded = true;

        $this->updateGlobalEnv();

        return $this;
    }

    /**
     * Store and update the environment variables.
     *
     * @param array $vars An array of variables to store.
     * @param bool|null $overwrite
     * @return void
     */
    private function store(array $vars, bool $overwrite = null): void
    {
        if ($overwrite === null) {
            $overwrite = false;
        }

        foreach ($vars as $name => $value) {
            if ($this->match($name)) {
                if ($overwrite) {
                    $this->local[$name] = $value;
                } else {
                    if (!isset($this->local[$name])) {
                        $this->local[$name] = $value;
                    }
                }
            }
        }
    }

    /**
     * Matches the variable name(s) against the allowed pattern.
     *
     * @param string|array $vars The variable name(s) to match against the pattern.
     *
     * @throws FormatException If any variable name doesn't match the allowed pattern.
     *
     * @return bool True if the variable name(s) match the pattern or if an empty array is passed, otherwise false.
     */
    private function match(string|array $vars): bool
    {
        if (is_array($vars)) {
            if (empty($vars)) {
                return true;
            }
        }

        $array = [];

        if (is_string($vars)) {
            $array[$vars] = "";
        }

        foreach ($array as $name => $value) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z_.]*$/', $name)) {
                throw FormatException::NotAllowedVarNameFormat($name);
            }
        }

        return true;
    }

    /**
     * Update the global environment variables.
     *
     * @param array|null $default An array of default variables.
     * @return void
     */
    private function updateGlobalEnv(array $default = null): void
    {
        $previousKeys = isset($_SERVER['_NOVA_FRAME_ENV_KEYS'])
            ? explode(',', $_SERVER['_NOVA_FRAME_ENV_KEYS'])
            : $this->envKeys;

        if (!empty($default)) {
            $this->local = array_merge($this->local, $default);
        }

        if (!empty($this->local) && $this->match($this->local)) {

            $envKeys = [];

            foreach ($this->local as $key => $value) {
                putenv(sprintf("%s=%s", $key, $value));
                $envKeys[] = $key;
            }

            $this->envKeys = $envKeys;

            $keyDiff = array_diff_key($previousKeys, $this->envKeys);

            if (!empty($keyDiff)) {
                foreach ($keyDiff as $key => $value) {
                    unset($_SERVER[$key]);
                    putenv($key);
                    unset($_ENV[$key]);
                }
            }

            $this->global = $_ENV = $this->local;

            $_SERVER += $this->global;
            $_SERVER['_NOVA_FRAME_ENV_KEYS'] = implode(',', $this->envKeys);
        }
    }

    /**
     * Remove Double Quote & Single Quote from string
     *
     * @param $value
     * @return string
     */
    private function removeQuotes($value): string
    {
        return str_replace(['"', "'"], '', $value);
    }
}
