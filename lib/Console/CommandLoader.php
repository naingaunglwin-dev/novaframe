<?php

namespace NovaFrame\Console;

use NovaFrame\Helpers\Path\Path;

class CommandLoader
{
    private string $namespace = "App\\Commands\\";

    public function __construct(
        private string $path
    )
    {
        $this->path = Path::normalize($this->path);
    }

    public function load(): array
    {
        $found = [];

        if (!is_dir($this->path)) {
            return $found;
        }

        $files = glob($this->path . '/*.php');

        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            if (class_exists($this->namespace . ucfirst($name))) {
                $class = $this->namespace . ucfirst($name);
                $class = new $class();

                if ($class instanceof Command) {
                    $found[] = $class;
                }
            }
        }

        return $found;
    }
}
