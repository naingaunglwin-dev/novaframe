<?php

namespace Nova\Helpers\Modules;

use Random\Engine\Mt19937;
use Random\Randomizer;

class Str
{
    /**
     * Alphabet characters
     *
     * @var array
     */
    private static array $alphabets = [
        'lowercase' => [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        ],
        'uppercase' => [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ]
    ];

    /**
     * Numeric characters
     *
     * @var array
     */
    private static array $numbers = [
        '1', '2', '3', '4', '5', '6', '7', '8', '9'
    ];

    /**
     * Special characters
     *
     * @var array
     */
    private static array $specialChars = [
        '@', '?', '+', '&', '#', '!', '^', '-', '!'
    ];

    /**
     * Splits a string by dots and returns an array of trimmed, non-empty elements.
     *
     * This method splits the given string by dots (.) and returns an array. Optionally, it can also remove
     * all whitespace characters from the string before splitting.
     *
     * @param string $string The input string to be split.
     * @param bool $trim Optional. Whether to trim and remove all whitespace characters from the string before splitting. Default is true.
     * @return array An array of trimmed, non-empty elements obtained by splitting the input string by dots.
     */
    public static function dot2Array(string $string, bool $trim = true): array
    {
        return self::toArray(".", $string, $trim);
    }

    /**
     * Splits a string by a specified separator and returns an array of trimmed, non-empty elements.
     *
     * This method splits the given string by the specified separator and returns an array. Optionally, it can also remove
     * all whitespace characters from the string before splitting.
     *
     * @param string $separator The separator used to split the input string.
     * @param string $string The input string to be split.
     * @param bool $trim Optional. Whether to trim and remove all whitespace characters from the string before splitting. Default is true.
     * @return array An array of trimmed, non-empty elements obtained by splitting the input string by the specified separator.
     */
    public static function toArray(string $separator, string $string, bool $trim = true): array
    {
        if (empty($string)) {
            return [];
        }

        if ($trim) {
            $string = self::trim($string);
        }

        $array = explode($separator, $string);

        return array_filter(
            array_map('trim', $array),
            function ($value) {
                return !empty($value);
            }
        );
    }

    /**
     * Removes all whitespace characters from the beginning and end of a string.
     *
     * @param string $string The input string to be trimmed.
     * @return string The trimmed string with all whitespace characters removed.
     */
    public static function trim(string $string): string
    {
        return preg_replace('/\s+/', '', trim($string));
    }

    /**
     * Converts a string to uppercase with options for capitalization.
     *
     * @param string $string The input string to be converted.
     * @param bool $ucfirst Optional. Whether to capitalize the first character only. Default is false.
     * @param bool $ucwords Optional. Whether to capitalize the first character of each word. Default is false.
     * @param bool $trim Optional. Whether to trim the string before conversion. Default is false.
     * @return string The string converted to uppercase with the specified options.
     */
    public static function toUpper(string $string, bool $ucfirst = false, bool $ucwords = false, bool $trim = false): string
    {
        if ($trim) {
            $string = static::trim($string);
        }

        if ($ucfirst) {
            $string = ucfirst($string);
        }

        if ($ucwords) {
            $string = ucwords($string);
        }

        if ($ucfirst || $ucwords) {
            return $string;
        }

        return mb_strtoupper($string);
    }

    /**
     * Converts a string to lowercase.
     *
     * @param string $string The input string to be converted.
     * @param bool $lcfirst Optional. Whether to lowercase the first character only. Default is false.
     * @param bool $trim Optional. Whether to trim the string before conversion. Default is false.
     * @return string The string converted to lowercase.
     */
    public static function toLower(string $string, bool $lcfirst = false, bool $trim = false): string
    {
        if ($trim) {
            $string = Str::trim($string);
        }

        if ($lcfirst) {
            return lcfirst($string);
        }

        return mb_strtolower($string);
    }

    /**
     * Escapes special characters in a string for HTML output.
     *
     * This method converts special characters in the input string to their
     * corresponding HTML entities, making the string safe for output in HTML.
     *
     * @param string $string The input string to be escaped.
     * @param string $encode Optional. The character encoding to use (default is 'UTF-8').
     * @return string The escaped string with special characters converted to HTML entities.
     */
    public static function escape(string $string, string $encode = 'UTF-8'): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, $encode);
    }

    /**
     * Unescapes special HTML entities back to plain text.
     *
     * This method converts HTML entities in the input string back to their
     * corresponding special characters.
     *
     * @param string $string The HTML-encoded string to be unescaped.
     * @return string The string with HTML entities decoded back to special characters.
     */
    public static function unescape(string $string): string
    {
        return htmlspecialchars_decode($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
    }

    /**
     * Wraps a string with the given start and end wrapping strings.
     * If the end wrapping string is not provided, it defaults to the start wrapping string.
     *
     * @param string $string The input string to be wrapped.
     * @param string $start The string to wrap around the beginning of the input string.
     * @param string|null $end Optional. The string to wrap around the end of the input string. Defaults to the start wrapping string.
     * @return string The wrapped string.
     */
    public static function wrap(string $string, string $start, string $end = null): string
    {
        return sprintf("%s%s%s", $start, $string, $end ?? $start);
    }

    /**
     * Unwraps a string by removing the given start and end wrapping strings.
     *
     * This method removes the specified start and end wrapping strings from the input string.
     * If the end wrapping string is not provided, it defaults to the start wrapping string.
     *
     * @param string $string The input string to be unwrapped.
     * @param string $start The string to remove from the beginning of the input string.
     * @param string|null $end Optional. The string to remove from the end of the input string. Defaults to the start wrapping string.
     * @return string The unwrapped string.
     */
    public static function unwrap(string $string, string $start, string $end = null): string
    {
        if (empty($end)) {
            $end = $start;
        }

        if (static::startwith($string, $start)) {
            $string = substr($string, strlen($start));
        }

        if (static::endwith($string, $end)) {
            $string = substr($string, 0, -strlen($start));
        }

        return $string;
    }

    /**
     * Checks if a string starts with a given substring.
     *
     * @param string $haystack The string to search in.
     * @param string $needle The substring to search for.
     * @param bool $casesensitive Optional. Whether the comparison should be case-sensitive. Default is true.
     * @return bool True if haystack starts with needle, false otherwise.
     */
    public static function startwith(string $haystack, string $needle, bool $casesensitive = true): bool
    {
        if (!$casesensitive) {
            $haystack = self::toLower($haystack);
            $needle   = self::toLower($needle);
        }

        return str_starts_with($haystack, $needle);
    }

    /**
     * Checks if a string ends with a given substring.
     *
     * @param string $haystack The string to search in.
     * @param string $needle The substring to search for.
     * @param bool $caseSensitive Optional. Whether the comparison should be case-sensitive. Default is true.
     * @return bool True if haystack ends with needle, false otherwise.
     */
    public static function endwith(string $haystack, string $needle, bool $caseSensitive = true): bool
    {
        if (!$caseSensitive) {
            $haystack = self::toLower($haystack);
            $needle   = self::toLower($needle);
        }

        return str_ends_with($haystack, $needle);
    }

    /**
     * Generate a random string
     *
     * @param int $length Token length
     * @param bool $specialchars Whether to use special characters in token string
     * @return string
     */
    public static function random(int $length = 16, bool $specialchars = false): string
    {
        $chars = array_merge(self::$alphabets['lowercase'], self::$alphabets['uppercase'], self::$numbers, $specialchars ? self::$specialChars : []);

        shuffle($chars);

        $randomizer = new Randomizer(new Mt19937((int) dechex(mt_rand(0, $length))));

        while (count($chars) < $length) {
            $chars = array_merge($chars, array_slice($chars, 0, $length - count($chars)));
        }

        $array = $randomizer->pickArrayKeys($chars, $length);

        shuffle($array);

        $random = "";

        foreach ($array as $value) {
            $random .= $chars[$value];
        }

        return $random;
    }
}
