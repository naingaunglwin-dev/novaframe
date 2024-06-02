<?php

/**
 * This file is part of NOVA FRAME framework
 *
 * @copyright (c) Naing Aung Lwin
 * @link https://github.com/naingaunglwin-dev/novaframe
 * @licence MIT
 */

namespace Nova\Service\Language;

class Language
{
    /**
     * Application's locale
     *
     * @var string
     */
    private string $locale;

    /**
     * Loaded Messages
     *
     * @var array
     */
    private array $messages = [];

    public function __construct()
    {
        $this->locale = app()->getLocale();
    }

    /**
     * Get a translated message from the language file.
     *
     * @param string $key The message name in the format "file.message".
     * @param string ...$placeholder Placeholder values to replace in the message.
     * @return mixed The translated message or null if not found.
     */
    public function getMessage(string $key, string ...$placeholder): mixed
    {
        $this->load($key);

         if (isset($this->messages[$this->locale][$key])) {
             $message = $this->messages[$this->locale][$key];

             if (!empty($placeholder)) {
                 return vsprintf($message, $placeholder);
             }

             return $message;
         }

        return null;
    }

    /**
     * Load language data for the specified keys.
     *
     * This method loads language data for the specified keys from language files based on the current locale.
     *
     * @param string $keys The dot-separated keys representing the language data to load.
     * @return void
     */
    private function load(string $keys): void
    {
        $oldKey = $keys;

        $return[$oldKey] = null;

        $path = config('app.paths.language');

        if (!str_ends_with($path, DIRECTORY_SEPARATOR)) {
            $path .= DIRECTORY_SEPARATOR;
        }

        $keys = explode('.', $keys);

        if (!empty($keys)) {
            $fileName = array_shift($keys);

            $file = $path . $this->locale . "/" . $fileName;

            if (!is_dir($file)) {
                $file = $file . ".php";
            }

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

                $return[$oldKey] = $data;
            }
        }

        $this->messages[$this->locale] = $return;
    }
}
