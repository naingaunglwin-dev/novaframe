<?php

namespace NovaFrame\Http;

class RequestSanitizer
{
    public static function sanitize($source, $data)
    {
        $source = strtoupper($source);

        return match ($source) {
            'GET', 'POST', 'COOKIE' => static::recursiveSanitize($data),
            'FILES' => static::formatFile($data),
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

    private static function formatFile(array $files)
    {
        $formatted = [];

        if (empty($files)) {
            return $formatted;
        }

        foreach ($files as $input => $file) {
            if (is_array($file['name'])) {
                foreach ($file['name'] as $index => $_) {
                    $formatted[$input][] = new File([
                        'name'     => $file['name'][$index],
                        'type'     => $file['type'][$index],
                        'size'     => $file['size'][$index],
                        'tmp_name' => $file['tmp_name'][$index],
                        'error'    => $file['error'][$index],
                    ]);
                }
            } else {
                $formatted[$input] = new File($file);
            }
        }

        return $formatted;
    }
}
