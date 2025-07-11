<?php

namespace NovaFrame\Language;

use NovaFrame\Helpers\Path\Path;
use NovaFrame\Language\Exceptions\PathNotFound;

class Language
{
    /**
     * The current application locale.
     *
     * @var string
     */
    private string $locale;

    /**
     * Loaded language messages.
     *
     * @var array<string, array>
     */
    private array $messages = [];

    /**
     * Cached compiled language file path.
     *
     * @var string
     */
    private string $cachedFile = DIR_BOOTSTRAP . 'cache' . DS . 'language.php';

    /**
     * Whether the cached language file is already loaded.
     *
     * @var bool
     */
    private bool $loaded = false;

    /**
     * Language constructor.
     */
    public function __construct()
    {
        $this->locale = app()->locale();
    }

    /**
     * Get the translated message for the given key.
     *
     * @param string $key     Dot-notated key (e.g., 'auth.failed').
     * @param array<string, string> $params Optional parameters to replace in the message.
     *
     * @return string
     *
     * @throws PathNotFound
     */
    public function get(string $key, array $params = []): string
    {
        [$file, $keys] = $this->retrieveFileFromKey($key);

        $this->load($file);

        return $this->find($file, $keys, $params);
    }

    /**
     * Load the language file into memory.
     *
     * @param string $file
     * @return void
     */
    private function load(string $file): void
    {
        if (config('app.env', 'production') === 'production') {
            if ($this->loaded) {
                return;
            }

            $path = $this->cachedFile;
        } else {
            $path = Path::join(DIR_APP, 'Languages', $this->locale, $file);
        }

        $this->messages[$this->locale][$file] = require $path;
    }

    /**
     * Traverse the loaded messages array to find the correct translation.
     *
     * @param string $file
     * @param array<int, string> $keys
     * @param array<string, string> $params
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function find(string $file, array $keys, array $params = []): string
    {
        $message = $this->messages[$this->locale][$file];

        foreach ($keys as $key) {
            if (isset($message[$key])) {
                $message = $message[$key];
            } else {
                $message = null;
                break;
            }
        }

        if (empty($message)) {
            throw new \InvalidArgumentException('Unable to find key "' . implode('.', $keys) . '" in ' . $this->locale . '/' . $file);
        }

        if (!empty($params)) {
            $message = strtr($message, array_combine(
                array_map(fn($k) => '{{' . $k . '}}', array_keys($params)),
                array_values($params)
            ));
        }

        return $message;
    }

    /**
     * Extract the file name and keys from the dot-notated language key.
     *
     * @param string $key
     * @return array{string, array<int, string>}
     *
     * @throws PathNotFound
     */
    private function retrieveFileFromKey(string $key): array
    {
        $keys = explode('.', $key);

        $file = array_shift($keys) . '.php';

        $path = Path::join(DIR_APP, 'Languages', $this->locale, $file);

        if (!file_exists($path)) {
            throw new PathNotFound($path);
        }

        return [$file, $keys];
    }
}
