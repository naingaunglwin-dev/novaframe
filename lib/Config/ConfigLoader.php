<?php

namespace NovaFrame\Config;

use NovaFrame\Env\Env;
use NovaFrame\Helpers\Path\Path;

class ConfigLoader
{
    /**
     * Path to the cached configuration file.
     *
     * @var string
     */
    private string $cachePath = DIR_BOOTSTRAP . 'cache' . DS . 'config.php';

    /**
     * Load a configuration file, supporting both cached and uncached environments.
     *
     * If the environment is production and the config file has not been cached yet,
     * it will be loaded and stored in the `$cached` array. If caching is enabled,
     * this method returns only the subset of config matching the requested filename.
     *
     * @param string $file The base name of the config file (e.g., 'app', 'database').
     * @param array<string, array> &$cached Reference to the cache array where loaded config files are stored.
     * @return array The resolved configuration data.
     *
     * @throws \InvalidArgumentException If the config file does not exist.
     */
    public function load(string $file, array &$cached)
    {
        $filename = $file;
        $file = file_exists($this->cachePath) ? $this->cachePath : Path::join(DIR_CONFIG, $file . '.php');

        if (!file_exists($file)) {
            throw new \InvalidArgumentException("The config file '$file' does not exist");
        }

        if (Env::get('app.env', 'production') === 'production') {
            if (!isset($cached[$file])) {
                $cached[$file] = require $file;
            }

            return $this->value($cached[$file], $filename);
        }

        return $this->value(require $file, $filename);
    }

    /**
     * Retrieve the config array corresponding to a single file name from a full config set.
     *
     * If using the cache file (which includes all configs keyed by filename),
     * this will return only the values under that specific key. Otherwise,
     * it simply returns the full config array.
     *
     * @param array $configs The loaded configuration data.
     * @param string $name The name of the config file to extract (e.g., 'app', 'database').
     * @return array The specific config values for the requested file.
     */
    public function value(array $configs, string $name)
    {
        return file_exists($this->cachePath)
            ? $configs[$name] // get the config values from cache/config.php with respective file name array key
            : $configs; // get the config with filename from config/filename.php
    }
}
