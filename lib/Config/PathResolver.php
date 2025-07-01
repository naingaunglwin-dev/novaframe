<?php

namespace NovaFrame\Config;

use NovaFrame\Helpers\Path\Path;

class PathResolver
{
    /**
     * Path to the cached configuration file.
     *
     * @var string
     */
    private string $cachePath = DIR_BOOTSTRAP . 'cache/config.php';

    /**
     * Resolve the absolute path to a configuration file.
     *
     * If a cached config file exists, it will be returned.
     * Otherwise, this method will resolve the full path to the
     * requested config file inside the config directory.
     *
     * @param string $file The config file name (e.g., 'app' or 'database').
     *                     The `.php` extension is optional.
     * @return string The resolved absolute file path.
     *
     * @throws \InvalidArgumentException If the config file does not exist.
     */
    public function resolve($file): string
    {
        if (file_exists($this->cachePath)) {
            return $this->cachePath;
        }

        $file = Path::join(DIR_CONFIG, $file);

        if (pathinfo($file, PATHINFO_EXTENSION) === '' || pathinfo($file, PATHINFO_EXTENSION) === '0') {
            $file .= '.php';
        }

        if (!file_exists($file)) {
            throw new \InvalidArgumentException($file . ' config file does not exist');
        }

        return $file;
    }
}
