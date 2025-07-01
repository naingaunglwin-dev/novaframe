<?php

namespace NovaFrame;

class RuntimeEnv
{
    public static function env(): string
    {
        if (
            defined('STDIN')
            || php_sapi_name() == 'cli'
            || (stristr(PHP_SAPI, 'cgi') && getenv('TERM'))
            || (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0)
        ) {
            return 'cli';
        }

        return 'web';
    }

    public static function envIs(string $env): bool
    {
        if (!in_array($env, ['cli', 'web'])) {
            return false;
        }

        return strtolower($env) === static::env();
    }

    public static function os(bool $short = false): string
    {
        return php_uname($short ? 's' : 'a');
    }

    public static function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    public static function isLinux(): bool
    {
        return stripos(PHP_OS, 'Linux') !== false;
    }

    public static function isMac(): bool
    {
        return stripos(PHP_OS, 'Darwin') !== false;
    }

    public static function getMemoryUsage(bool $real_usage = false)
    {
        return memory_get_usage($real_usage);
    }
}
