<?php

namespace NovaFrame\Helpers\Path;

class Path
{
        /**
     * Join multiple path segments into one normalized path.
     *
     * Example:
     * ```
     * Path::join('/var', 'www', 'html'); // returns "/var/www/html"
     * Path::join('C:\\', 'Users', 'Public'); // returns "C:\Users\Public" on Windows
     * ```
     *
     * @param string ...$paths One or more path segments.
     * @return string The joined, normalized path.
     */
    public static function join(string ...$paths): string
    {
        if (empty($paths)) {
            return '';
        }

        $first = array_shift($paths);
        $first = rtrim($first, DS);

        $segments = [$first];

        foreach ($paths as $path) {
            $normalized = static::normalize($path);
            $segments[] = $normalized;
        }

        $joined = implode(DS, $segments);

        if (empty($joined)) {
            return $joined;
        }

        return static::isAbsolute($first) ? $joined : DS . ltrim($joined, DS);
    }

    /**
     * Check if a given path is absolute.
     *
     * Supports both Unix-like absolute paths (starting with "/")
     * and Windows absolute paths (e.g., "C:\").
     *
     * @param string $path The path to check.
     * @return bool True if path is absolute, false otherwise.
     */
    public static function isAbsolute(string $path): bool
    {
        $isAbsolute = false;

        // Normalize first segment and detect if it's absolute
        $path = str_replace(['/', '\\'], DS, $path);
        if (str_starts_with($path, DS) || preg_match('/^[A-Za-z]:\\\\/', $path)) {
            $isAbsolute = true;
        }

        return $isAbsolute;
    }

    /**
     * Normalize a path segment by replacing slashes with directory separator
     * and trimming leading and trailing directory separators.
     *
     * @param string $path The path segment to normalize.
     * @return string Normalized path segment without leading/trailing slashes.
     */
    public static function normalize(string $path): string
    {
        return trim(str_replace(['/', '\\'], DS, $path), DS);
    }
}
