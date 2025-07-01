<?php

namespace NovaFrame\Helpers\FileSystem;

class FileSystem
{
    /**
     * Create a directory if it does not exist.
     *
     * @param string $path Directory path to create.
     * @param int $mode Permissions mode (default 0777).
     * @param bool $recursive Whether to create nested directories recursively.
     * @return bool True on success, false if directory already exists or creation failed.
     */
    public static function mkdir(string $path, int $mode = 0777, bool $recursive = false): bool
    {
        if (is_dir($path)) {
            return false;
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Recursively remove a directory and its contents.
     *
     * @param string $dir Directory path to remove.
     * @return bool True on success, false if directory does not exist or removal failed.
     */
    public static function rmdir(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        foreach (new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS) as $item) {
            $path = $item->getPathname();

            if ($item->isDir() && !$item->isLink()) {
                static::rmdir($path);
            } else {
                static::unlink($path);
            }
        }

        return rmdir($dir);
    }

    /**
     * Write content to a file.
     *
     * @param string $file File path to write to.
     * @param string $content Content to write.
     * @param bool $override Whether to overwrite the file (true) or append (false).
     * @param bool $throw Whether to throw an exception on failure (default false).
     * @return bool True on success, false on failure if $throw is false.
     * @throws \RuntimeException If unable to open file and $throw is true.
     */
    public static function fwrite(string $file, string $content, bool $override = false, bool $throw = false): bool
    {
        $dir = dirname($file);

        if (!is_dir($dir)) {
            static::mkdir($dir, recursive: true);
        }

        $handler = fopen($file, $override ? 'w' : 'a');

        if (!$handler) {
            if ($throw) {
                throw new \RuntimeException('Unable to open file: ' . $file);
            }

            return false;
        }

        fwrite($handler, $content);
        fclose($handler);
        return true;
    }

    /**
     * Write content to a file, always throwing exception on failure.
     *
     * @param string $file File path to write to.
     * @param string $content Content to write.
     * @param bool $override Whether to overwrite the file (true) or append (false).
     * @return bool True on success.
     * @throws \RuntimeException On failure to open or write.
     */
    public static function fwriteOrThrow(string $file, string $content, bool $override = false): bool
    {
        return static::fwrite($file, $content, $override, true);
    }

    /**
     * Read content from a file.
     *
     * @param string $file File path to read from.
     * @param int|null $length Number of bytes to read, or null to read entire file.
     * @param bool $throw Whether to throw exception if file not readable (default false).
     * @return string|false File contents on success, false on failure if $throw is false.
     * @throws \RuntimeException If unable to open or read file and $throw is true.
     */
    public static function fread(string $file, ?int $length = null, bool $throw = false): string|false
    {
        if (!is_file($file) || !is_readable($file)) {
            if ($throw) {
                throw new \RuntimeException('Unable to open file or read: ' . $file);
            }
            return false;
        }

        $length ??= filesize($file);

        $handler = fopen($file, 'r');

        if (!$handler) {
            if ($throw) {
                throw new \RuntimeException('Unable to open file: ' . $file);
            }
            return false;
        }

        $content = fread($handler, $length);
        fclose($handler);

        return $content;
    }

    /**
     * Read content from a file and always throw on failure.
     *
     * @param string $file File path to read from.
     * @param int|null $length Number of bytes to read, or null for entire file.
     * @return string File contents.
     * @throws \RuntimeException On failure to open or read.
     */
    public static function freadOrThrow(string $file, ?int $length = null): string
    {
        return static::fread($file, $length, true);
    }

    /**
     * Delete a file or symbolic link.
     *
     * @param string $file File path to delete.
     * @return bool True on success, false if file does not exist or deletion failed.
     */
    public static function unlink(string $file): bool
    {
        if (!file_exists($file) && !is_link($file)) {
            return false;
        }

        return unlink($file);
    }

    /**
     * Copy a file from source to destination.
     *
     * @param string $from Source file path.
     * @param string $to Destination file path.
     * @param bool $override Whether to overwrite destination if it exists (default false).
     * @return bool True on success, false if source doesn't exist or destination exists without override.
     */
    public static function copy(string $from, string $to, bool $override = false): bool
    {
        if (!file_exists($from)) {
            return false;
        }

        if (file_exists($to) && !$override) {
            return false;
        }

        return copy($from, $to);
    }
}
