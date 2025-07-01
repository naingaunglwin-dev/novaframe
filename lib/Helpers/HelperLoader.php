<?php

namespace NovaFrame\Helpers;

use NovaFrame\Helpers\Exceptions\PathNotFound;
use NovaFrame\Helpers\Exceptions\UndefinedHelper;

class HelperLoader
{
    private static ?HelperLoader $instance = null;

    private array $loaded = [];

    private array $helpers = [];

    private bool $isHelperConfigFileLoaded = false;

    public function load(string ...$name): void
    {
        $helpers = $this->getHelpers();

        if ($name === ['*']) {
            foreach ($helpers as $key => $path) {
                $this->loadHelper($key, $path);
            }
        } else {
            foreach ($name as $helper) {

                $key = strtolower($helper);

                if (!array_key_exists($key, $helpers)) {
                    throw new UndefinedHelper($helper);
                }

                $this->loadHelper($key, $helpers[$key]);
            }
        }
    }

    private function loadHelper(string $key, string $value): void
    {
        $env = config("app.env", 'production');

        if (in_array($key, $this->loaded, true) && $env === 'production') {
            return;
        }

        if (!file_exists($value)) {
            throw new PathNotFound($value);
        }

        require_once $value;

        $this->loaded[] = $key;
    }

    private function getHelpers(): array
    {
        if ($this->isHelperConfigFileLoaded && config('app.env', 'production') === 'production') {
            return $this->helpers;
        }

        $this->isHelperConfigFileLoaded = true;
        $this->helpers = config('helpers');

        return $this->helpers;
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof \NovaFrame\Helpers\HelperLoader) {
            self::$instance = new HelperLoader();
        }

        return self::$instance;
    }
}
