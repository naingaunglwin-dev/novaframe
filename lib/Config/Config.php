<?php

namespace NovaFrame\Config;

use NovaFrame\Facade\Env;

class Config
{
    /**
     * Cached configuration files, keyed by absolute path.
     *
     * @var array<string, array>
     */
    private array $cached = [];

    /**
     * Get a configuration value by dot notation key.
     *
     * @param string $key The configuration key (e.g., 'app.name').
     * @param mixed|null $default The default value if the key does not exist.
     * @return mixed The configuration value or the default.
     */
    public function get(string $key, $default = null)
    {
        return $this->resolve($key, $default);
    }

    /**
     * Resolve a configuration value by loading the appropriate file and parsing the nested keys.
     *
     * @param string $key The configuration key.
     * @param mixed|null $default The default value if not found.
     * @return mixed The resolved configuration value.
     */
    private function resolve(string $key, $default = null)
    {
        $keys = explode('.', $key);

        if ($keys === []) {
            return $default;
        }

        $filename = $keys[0];
        unset($keys[0]);

        $path = (new PathResolver())->resolve($filename);

        if (Env::get('APP_ENV', 'production') === 'production') {
            if (!isset($this->cached[$path])) {
                $this->cached[$path] = require $path;
            }
            $configs = $this->cached[$path];
        } else {
            $configs = require $path;
        }

        foreach ($keys as $key) {
            if (isset($configs[$key])) {
                $configs = $configs[$key];
            } else {
                $configs = $default;
                break;
            }
        }

        return $configs;
    }
}
