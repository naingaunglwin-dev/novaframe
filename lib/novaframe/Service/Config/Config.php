<?php

namespace Nova\Service\Config;

class Config
{
    /**
     * Config path
     *
     * @var string
     */
    private string $path = APP_PATH . 'Config/';

    /**
     * Store path
     *
     * @var string
     */
    private string $storePath = BOOTSTRAP_PATH . 'cache/config.cache.php';

    /**
     * Retrieves the value associated with the specified configuration key.
     *
     * @param string $name The dot-separated configuration key.
     * @return mixed The value associated with the key, or null if not found.
     */
    public function get(string $name): mixed
    {
        return $this->resolve($name) ?? null;
    }

    /**
     * Resolves the value associated with the specified configuration key.
     *
     * This method retrieves configuration values by traversing through the configuration array
     * loaded from PHP files. It supports dot notation to access nested configuration values.
     *
     * If the specified configuration key is found, the resolved value is stored in the internal
     * data array. If the key is not found or an error occurs while resolving the value, the
     * corresponding entry in the data array will be set to null.
     *
     * @param string $name The dot-separated configuration key.
     *
     * @return mixed
     */
    private function resolve(string $name): mixed
    {
        $result[$name] = null;

        $keys = explode(".", $name);

        if (!empty($keys)) {
            $file = $keys[0];

            unset($keys[0]);

            $file = $this->path . $file . ".php";

            if (file_exists($this->storePath)) {

                $data = require $this->storePath;

                return $this->doResolve($keys, $data[pathinfo($file, PATHINFO_FILENAME)] ?? []);
            }

            if (file_exists($file)) {
                return $this->doResolve($keys, require $file);
            }

            return null;
        }

        return null;
    }

    /**
     * Resolve the process with given keys and data array
     *
     * @param $keys
     * @param $data
     * @return mixed|null
     */
    private function doResolve($keys, $data): mixed
    {
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                $data = null;
                break;
            }
        }

        return $data;
    }
}
