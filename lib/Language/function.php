<?php

use NovaFrame\Facade\Language;

if (!function_exists('lang')) {
    /**
     * Get the translated language string for the given key.
     *
     * @param string $key     Dot-notated language key (e.g., 'auth.failed')
     * @param array<string, string> $params  Parameters to replace in the translation
     * @return string
     */
    function lang(string $key, array $params = []): string
    {
        return Language::get($key, $params);
    }
}
