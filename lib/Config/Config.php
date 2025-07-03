<?php

namespace NovaFrame\Config;

class Config
{
    /**
     * Cached configuration files, keyed by absolute path.
     *
     * @var array<string, array>
     */
    private array $cached = [];

    /**
     * @var ConfigLoader
     */
    private ConfigLoader $loader;

    /**
     * Config Constructor
     */
    public function __construct()
    {
        $this->loader = new ConfigLoader();
    }

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
        if (empty($key)) {
            return $default;
        }

        $keys = explode('.', $key);
        $filename = array_shift($keys);

        $configs = $this->loader->load($filename, $this->cached);

        foreach ($keys as $key) {
            if (!is_array($configs) || !array_key_exists($key, $configs)) {
                return $default;
            }

            $configs = $configs[$key];
        }

        return $configs;
    }
}
