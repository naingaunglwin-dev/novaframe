<?php

namespace NovaFrame\Env;

use NovaFrame\Env\Exceptions\InvalidEnvKeyFormat;
use NovaFrame\Env\Exceptions\PathNotFound;

class Env
{
    /**
     * Loaded environment variables.
     *
     * @var array<string, string>
     */
    private array $envs = [];

    /**
     * Whether the env files have already been loaded.
     *
     * @var bool
     */
    private bool $loaded = false;

    /**
     * Grouped environment variables (e.g. DB_HOST, DB_USER under 'DB').
     *
     * @var array<string, array<string, string>>
     */
    private array $group = [];

    /**
     * Keys of loaded environment variables.
     *
     * @var array<int, string>
     */
    private array $keys = [];

    /**
     * Default file names to look for when no specific .env file is given.
     *
     * @var array<int, string>
     */
    private array $defaultEnvFiles = [
        '.env', '.env.local',
        '.env.development', '.env.production', '.env.testing',
        '.env.dev', '.env.prod', '.env.test'
    ];

    /**
     * Key used to store environment keys in $_SERVER.
     *
     * @var string
     */
    private const KEY = "NOVAFRAME_ENV_KEYS";

    /**
     * Dotenv constructor
     *
     * @param string|array|null $files     Optional path(s) to specific .env files.
     * @param bool              $override Whether to allow overwriting of previously set environment variables.
     */
    public function __construct(
        private string|array|null $files = null,
        private readonly bool $override = true
    )
    {
        if (empty($this->files)) {
            $this->files = $this->findAvailableEnvFilesFromDefaultFiles(
                defined('ENVIRONMENT') && ENVIRONMENT === 'testing' ? true : false
            );
        } else {
            $this->files = $this->resolvePaths($this->files);
        }
    }

    /**
     * Get a specific environment variable or all of them.
     *
     * @param string|null $key     Variable name.
     * @param mixed       $default Default value if key is not found.
     * @return mixed
     */
    public function get(?string $key = null, mixed $default = null): mixed
    {
        if (!$this->loaded) {
            $this->load(); // ensure load env file before returning vars value
        }

        if (is_null($key)) {
            return $this->envs;
        }

        return $this->envs[$key] ?? $default;
    }

    /**
     * Get grouped environment variables (e.g. all DB_*).
     *
     * @param string|null $key     Group key (e.g. 'DB').
     * @param mixed       $default Default value if group is not found.
     * @return mixed
     */
    public function group(?string $key = null, mixed $default = null): mixed
    {
        if (!$this->loaded) {
            $this->load(); // ensure load env file before returning vars value
        }

        if (is_null($key)) {
            return $this->group;
        }

        return $this->group[$key] ?? $default;
    }

    /**
     * Check if a specific environment variable exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if (!$this->loaded) {
            $this->load();
        }

        return array_key_exists($key, $this->envs);
    }

    /**
     * Load environment variables from the defined files.
     *
     * @return $this
     */
    public function load(): Env
    {
        if ($this->loaded && $this->isAppInProduction()) {
            return $this;
        }

        return $this->reload();
    }

    /**
     * Check if the current environment is production.
     *
     * @return bool
     */
    private function isAppInProduction()
    {
        $env = strtolower($this->envs['APP_ENV'] ?? '');

        return in_array($env, ['production', 'prod'], true);
    }

    /**
     * Reload environment variables, clearing previously loaded ones.
     *
     * @return $this
     */
    public function reload(): Env
    {
        $this->restore(); // restore to initial stage first

        return $this->doLoad();
    }

    public function getDefaultEnvFiles(): array
    {
        return $this->defaultEnvFiles;
    }

    /**
     * Restore the internal state, removing all loaded environment data.
     *
     * @return void
     */
    private function restore()
    {
        $this->envs  = [];
        $this->group = [];
        $this->keys  = [];
    }

    /**
     * Load and parse the environment files.
     *
     * @return $this
     */
    private function doLoad()
    {
        foreach ($this->files as $file) {

            $handle = @fopen($file, "r");

            // @codeCoverageIgnoreStart
            if (!$handle) {
                continue; // skip to next file if file can't be opened
            }
            // @codeCoverageIgnoreEnd

            while (($line = fgets($handle)) !== false) {
                $line = trim($line);

                if ($this->isSkippableLines($line)) {
                    continue; // goes to next line if line is commented or empty
                }

                if (!str_contains($line, '=')) {
                    throw new \RuntimeException("Env var must contain '='");
                }

                [$key, $value] = explode("=", $line, 2);

                $key = $this->trim($key);
                $value = $this->trim($value);

                $this->ensureValidEnvKeyFormat($key, $file);

                if (!$this->override && in_array($key, array_keys($this->envs))) {
                    continue; // ensure variables not to be overwritten if overwrite is false
                }

                $this->envs[$key] = $value;

                if ($this->isGroupEnv($key)) {
                    $name = strtok($key, '_');

                    $this->group[$name][$key] = $value;
                }
            }

            fclose($handle);
        }

        $this->loaded = true;

        $this->saveOnServer();

        return $this;
    }

    /**
     * Save environment variables into $_SERVER and putenv(), and store the keys.
     *
     * @return void
     */
    private function saveOnServer(): void
    {
        $oldKeys = isset($_SERVER[self::KEY])
            ? explode(',', $_SERVER[self::KEY])
            : $this->keys;

        if (empty($this->envs)) {
            return;
        }

        $keyDiff = array_diff_key(array_flip($oldKeys), $this->envs);

        if (!empty($keyDiff)) {
            foreach ($keyDiff as $key => $value) {
                $this->remove($key); // remove the deleted envs
            }
        }

        foreach ($this->envs as $key => $var) {
            $_SERVER[$key] = $var;
            putenv("$key=$var");
            $_ENV[$key] = $var;
        }

        $this->keys = array_keys($this->envs);

        $_SERVER[self::KEY] = implode(',', $this->keys); // save current env keys in server
    }

    /**
     * Remove environment variable from $_SERVER, $_ENV, and system environment.
     *
     * @param string $key
     * @return void
     */
    private function remove(string $key): void
    {
        if (isset($_SERVER[$key])) {
            unset($_SERVER[$key]);
        }

        putenv($key);

        if (isset($_ENV[$key])) {
            unset($_ENV[$key]);
        }
    }

    /**
     * Check if the key is a grouped environment variable (contains underscore).
     *
     * @param string $key
     * @return bool
     */
    private function isGroupEnv(string $key): bool
    {
        return str_contains($key, '_');
    }

    /**
     * Normalize and trim quotes and whitespace from string.
     *
     * @param string $value
     * @return string
     */
    private function trim(string $value)
    {
        return preg_replace('/\s+/', '', trim($value, "\"'"));
    }

    /**
     * Determine if the line should be skipped (empty or comment).
     *
     * @param string $line
     * @return bool
     */
    private function isSkippableLines($line): bool
    {
        return $line === '' || str_starts_with($line, '#');
    }

    /**
     * Find all available default .env files in DIR_ROOT.
     *
     * @return array<int, string>
     */
    private function findAvailableEnvFilesFromDefaultFiles(bool $isTesting): array
    {
        $defaultFiles = $this->defaultEnvFiles;

        if ($isTesting) {
            $defaultFiles = ['.env.testing', '.env.test'];
        }

        return array_map(
            fn ($file) => DIR_ROOT . $file,
            array_filter($defaultFiles, fn ($file) => file_exists(DIR_ROOT . $file))
        );
    }

    /**
     * Validate the format of an environment variable key.
     *
     * @param string $key
     * @param string $file
     * @return void
     *
     * @throws InvalidEnvKeyFormat
     */
    private function ensureValidEnvKeyFormat(string $key, string $file): void
    {
        if (preg_match("/^[a-zA-Z_][a-zA-Z_.]*$/", $key)) {
            return;
        };

        throw new InvalidEnvKeyFormat($key, $file);
    }

    /**
     * Resolve and normalize paths provided to the constructor.
     *
     * @param array|string $path
     * @return array<int, string>
     *
     * @throws PathNotFound
     */
    private function resolvePaths(array|string $path): array
    {
        $paths = is_string($path) ? [$path] : $path;

        $resolved = [];

        foreach ($paths as $path) {
            $path = str_replace(DIR_ROOT, '', $path);
            $path = $this->normalizePath($path);

            if (!file_exists($path)) {
                throw new PathNotFound($path);
            }

            $resolved[] = $path;
        }

        return $resolved;
    }

    /**
     * Normalize a file path to use system-specific directory separators.
     *
     * @param string $path
     * @return string
     */
    private function normalizePath(string $path): string
    {
        return str_replace(['/', '\\'], DS, $path);
    }
}
