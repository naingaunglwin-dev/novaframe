<?php

namespace Nova\Service\Config;

class Config
{
    /**
     * Check config files are loaded or not
     *
     * @var bool
     */
    private bool $isLoaded = false;

    /**
     * Configuration data
     *
     * @var array
     */
    private array $data = [];

    /**
     * Config path
     *
     * @var string
     */
    private string $path = APP_PATH . 'Config/';

    /**
     * Cache path
     *
     * @var string
     */
    private string $cache = BOOTSTRAP_PATH . 'cache/config_cache.php';

    /**
     * Retrieves the value associated with the specified configuration key.
     *
     * @param string $name The dot-separated configuration key.
     * @return mixed The value associated with the key, or null if not found.
     */
    public function get(string $name): mixed
    {
        $this->resolve($name);

        return $this->data[$name] ?? null;
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
     * @return void
     */
    private function resolve(string $name): void
    {
        $return[$name] = null;

        $keys = explode('.', $name);

        if (!empty($keys)) {
            $fileName = $keys[0];

            unset($keys[0]);

            $file = $this->path . $fileName . ".php";

            if (file_exists($file)) {

                $data = require $file;

                foreach ($keys as $key) {
                    if (isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        $data = null;
                        break;
                    }
                }

                $return[$name] = $data;
            } else {
                $return[$name] = null;
            }
        } else {
            $return[$name] = null;
        }

        $this->data = $return;
    }
}
