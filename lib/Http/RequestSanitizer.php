<?php

namespace NovaFrame\Http;

class RequestSanitizer
{
    public static function sanitize($source, $data)
    {
        $source = strtoupper($source);

        return match ($source) {
            'GET', 'POST', 'COOKIE' => static::recursiveSanitize($data),
            'FILES' => $data,
            default => [],
        };
    }

    public static function sanitizeBody($data)
    {
        if (empty($data)) {
            return $data;
        }

        return static::recursiveSanitize($data);
    }

    private static function recursiveSanitize($data): array|string|false
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = static::recursiveSanitize($value);
            }
            return $data;
        }

        return filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
