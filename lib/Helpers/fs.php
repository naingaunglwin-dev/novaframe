<?php

use NovaFrame\Helpers\FileSystem\FileSystem;

if (!function_exists('fs_mkdir')) {
        /**
     * Create a directory if it does not exist.
     *
     * @param string $path Directory path to create.
     * @param int $mode Permissions mode (default 0777).
     * @param bool $recursive Whether to create nested directories recursively.
     *
     * @return bool True on success, false if directory already exists or creation failed.
     */
    function fs_mkdir($path, $mode = 0777, $recursive = false): bool
    {
        return FileSystem::mkdir($path, $mode, $recursive);
    }
}

if (!function_exists('fs_rmdir')) {
    /**
     * Recursively remove a directory and its contents.
     *
     * @param string $dir Directory path to remove.
     *
     * @return bool True on success, false if directory does not exist or removal failed.
     */
    function fs_rmdir(string $dir): bool
    {
        return FileSystem::rmdir($dir);
    }
}

if (!function_exists('fs_fwrite')) {
    /**
     * Write content to a file.
     *
     * @param string $file File path to write to.
     * @param string $content Content to write.
     * @param bool $override Whether to overwrite the file (true) or append (false).
     * @param bool $throw Whether to throw an exception on failure (default false).
     * @return bool True on success, false on failure if $throw is false.
     *
     * @throws \RuntimeException If unable to open file and $throw is true.
     */
    function fs_fwrite(string $file, string $content, bool $override = false): bool
    {
        return FileSystem::fwrite($file, $content, $override);
    }
}

if (!function_exists('fs_fread')) {
    /**
     * Read content from a file.
     *
     * @param string $file File path to read from.
     * @param int|null $length Number of bytes to read, or null to read entire file.
     * @param bool $throw Whether to throw exception if file not readable (default false).
     * @return string|false File contents on success, false on failure if $throw is false.
     *
     * @throws \RuntimeException If unable to open or read file and $throw is true.
     */
    function fs_fread(string $file, ?int $length = null): string
    {
        return FileSystem::fread($file, $length);
    }
}

if (!function_exists('fwriteOrThrow')) {
    /**
     * Write content to a file, always throwing exception on failure.
     *
     * @param string $file File path to write to.
     * @param string $content Content to write.
     * @param bool $override Whether to overwrite the file (true) or append (false).
     * @return bool True on success.
     *
     * @throws \RuntimeException On failure to open or write.
     */
    function fwriteOrThrow(string $file, string $content, bool $override = false): bool
    {
        return FileSystem::fwrite($file, $content, $override);
    }
}

if (!function_exists('freadOrThrow')) {
    /**
     * Read content from a file and always throw on failure.
     *
     * @param string $file File path to read from.
     * @param int|null $length Number of bytes to read, or null for entire file.
     * @return string File contents.
     *
     * @throws \RuntimeException On failure to open or read.
     */
    function freadOrThrow(string $file, ?int $length = null): string
    {
        return FileSystem::fread($file, $length);
    }
}

if (!function_exists('fs_unlink')) {
    /**
     * Delete a file or symbolic link.
     *
     * @param string $file File path to delete.
     *
     * @return bool True on success, false if file does not exist or deletion failed.
     */
    function fs_unlink(string $file): bool
    {
        return FileSystem::unlink($file);
    }
}
