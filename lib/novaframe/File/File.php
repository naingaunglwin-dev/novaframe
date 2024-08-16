<?php

namespace Nova\File;

use InvalidArgumentException;
use Nova\Exception\Exceptions\FileException;

class File
{
    /**
     * File
     *
     * @var string
     */
    private string $file;

    /**
     * Strict mode
     *
     * @var bool
     */
    private bool $strict;

    /**
     * Counters for open and close calls.
     *
     * @var array
     */
    private array $counts = [
        'open'  => [],
        'close' => []
    ];

    /**
     * File constructor
     *
     * @param string|null $file full path till to the file
     * @param bool $strict If strict true, File class will throw exception on some cases, otherwise return false
     *
     * Example. `C:\\wamp\www\session\index.php`
     */
    public function __construct(string $file = null, bool $strict = false)
    {
        $this->file = $file ?? "";

        $this->strict = $strict;
    }

    /**
     * Set the file path.
     *
     * @param string $file The file path.
     * @param bool $overwrite Whether to overwrite if file is already set
     * @return File
     */
    public function set(string $file, bool $overwrite = false): File
    {
        if ($overwrite || empty($this->file)) {
            $this->file = $file;
        }

        return $this;
    }

    /**
     * Get the file
     *
     * @return string|null
     */
    public function get(): string|null
    {
        return $this->file ?? null;
    }

    /**
     * Get the name of the file (without the extension).
     *
     * @return string|null The name of the file, or null if the file path is empty.
     */
    public function name(): string|null
    {
        return $this->getInfo("name");
    }

    /**
     * Get the file extension.
     *
     * @return string|null The file extension, or null if the file path is empty.
     */
    public function extension(string $case = "lowercase"): string|null
    {
        $extension = $this->getInfo("extension");

        if (empty($extension)) {
            return null;
        }

        return match (strtolower($case)) {
            "lowercase" => strtolower($extension),
            "uppercase" => strtoupper($extension),
            default => $extension
        };
    }

    /**
     * Get the base name of the file (including the extension).
     *
     * @return string|null The base name of the file, or null if the file path is empty.
     */
    public function basename(string $case = "lowercase"): string|null
    {
        return $this->getInfo("basename");
    }

    /**
     * Get the dir name of the file
     *
     * @return string|null The directory name, or null if the file path is empty.
     */
    public function dirname(): string|null
    {
        return $this->getInfo("dirname");
    }

    /**
     * Get the file size
     *
     * @return int|null
     */
    public function size(): int|null
    {
        return $this->getInfo("size");
    }

    /**
     * Get an array containing information about the file path.
     *
     * @return array|null An associative array with keys like 'dirname', 'basename', 'extension', and 'filename',
     *                    or null if the file path is empty.
     */
    public function infos(): array|null
    {
        return $this->getInfo('all');
    }

    /**
     * Check if the file exists.
     *
     * @return bool True if the file exists, false otherwise or if the file path is empty.
     */
    public function exists(): bool
    {
        if (empty($this->file)) return false;

        return file_exists($this->file);
    }

    /**
     * Get the information about the file based on the specified type
     *
     * @param string $type PathInfo type to return ('name', 'extension', 'basename', 'all', 'size', 'dirname' etc.).
     * @return array|false|int|string|null
     */
    private function getInfo(string $type): bool|int|array|string|null
    {
        if ($this->strict) {
            $this->checkFileSetup($this->file);
        }

        if (empty($this->file)) return null;

        return match ($type) {
            "name"      => pathinfo($this->file, PATHINFO_FILENAME),
            "basename"  => pathinfo($this->file, PATHINFO_BASENAME),
            "extension" => pathinfo($this->file, PATHINFO_EXTENSION),
            "dirname"   => pathinfo($this->file, PATHINFO_DIRNAME),
            "size"      => filesize($this->file),
            default     => pathinfo($this->file)
        };
    }

    /**
     * Check whether file is valid
     *
     * @param $file
     * @return void
     */
    private function checkFileSetup($file): void
    {
        if (empty($file)) {
            throw new InvalidArgumentException("File cannot be empty");
        }

        if (!file_exists($file)) {
            throw new InvalidArgumentException("File $file does not exist");
        }
    }

    /**
     * Throw an exception base on strict mode
     *
     * @param $exception
     * @return false Return false if strict is false; Otherwise throw an exception
     */
    private function throw($exception)
    {
        if ($this->strict) {
            throw $exception;
        }

        return false;
    }

    /**
     * Get the file content
     *
     * @return bool|string|null
     */
    public function content(): bool|string|null
    {
        return $this->exists() ? file_get_contents($this->file) : null;
    }

    /**
     * Write content to a file
     *
     * @param string $content The content to write to the file.
     * @param string $where The position to start the writing content. `end` or `start`
     * @param bool $overwrite Whether to overwrite if content in file already exists
     * @param bool $newline New line to append to the content
     * @return bool
     */
    public function write(string $content, string $where = 'end', bool $overwrite = false, bool $newline = false): bool
    {
        return $this->doWrite($content, $where, $overwrite, $newline);
    }

    /**
     * Include the given file using include
     *
     * @return false|void Return false on file doesn't exist if you set strict false;
     *                    Otherwise, throw exception on file doesn't exist
     */
    public function include()
    {
        return $this->includeAction(false);
    }

    /**
     * Include file only when given callback true
     *
     * @param callable $callback
     * @return void
     */
    public function includeWhen(callable $callback)
    {
        if (di()->callback($callback) === true) {
            $this->include();
        }
    }

    /**
     * Include the given file using include_once
     *
     * @return false|void Return false on file doesn't exist if you set strict false;
     *                    Otherwise, throw exception on file doesn't exist
     */
    public function includeOnce()
    {
        return $this->includeAction();
    }

    /**
     * File include action
     *
     * @param bool $once Whether to perform with include or include_once
     * @return false|void
     */
    private function includeAction(bool $once = true)
    {
        if ($this->exists()) {
            if ($once) {
                include_once $this->file;
            } else {
                include $this->file;
            }
        } else {
            return $this->throw(new \RuntimeException("File doesn't exist"));
        }
    }

    /**
     * Open a file with a specified mode.
     *
     * @param string $mode The mode in which to open the file (e.g., 'r', 'w', 'a').
     * @param bool $use_include_path Whether to search for the file in the include path.
     * @param resource|null $context A valid context resource or null.
     * @return resource|false The file pointer resource on success, or false on failure.
     */
    public function open(string $mode, bool $use_include_path = false, $context = null)
    {
        $this->counts['open'][] = true;

        return fopen($this->file, $mode, $use_include_path, $context);
    }

    /**
     * Close an open file stream.
     *
     * @param resource $stream The file pointer resource to close.
     * @return bool Returns true on success or false on failure.
     */
    public function close($stream): bool
    {
        $this->counts['close'][] = true;

        if (count($this->counts['close']) >= count($this->counts['open'])) {
            return $this->throw(new \BadMethodCallException("Cannot close stream: unmatched or excessive close operations."));
        }

        return fclose($stream);
    }

    /**
     * Delete the file if exists
     *
     * @return false|void
     */
    public function unlink()
    {
        if (!$this->exists()) {
            return $this->throw(FileException::pathNotFound($this->get()));
        } else {
            unlink($this->get());
        }
    }

    /**
     * Delete file only when given callback true
     *
     * @param callable $callback
     * @return void
     */
    public function unlinkWhen(callable $callback): void
    {
        if (di()->callback($callback) === true) {
            $this->unlink();
        }
    }

    /**
     * Actual write the content to a file
     *
     * @param string $content The content to write to the file.
     * @param string $where The position to start the writing content. `end` or `start`
     * @param bool $overwrite Whether to overwrite if content in file already exists
     * @param bool $newline New line to append to the content
     * @return bool
     */
    private function doWrite(string $content, string $where = "end", bool $overwrite = false, bool $newline = false): bool
    {
        if ($overwrite) {
            $mode = "w";

        } elseif ($where === "end") {
            $mode = "a";

            $content = $newline ? "\n" . $content : $content;
        } else {
            $mode = "c";

            $content = $newline ? $content . "\n" : $content;
        }

        $resource = $this->open($mode);

        if (
            $resource === false
            && !$this->throw(new \RuntimeException("Fail to load file"))
        ) {
            return false;
        }

        try {
            flock($resource, LOCK_EX);

            fwrite($resource, $content);

            flock($resource, LOCK_UN);

            $this->close($resource);

            return true;
        } catch (\Exception $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }

    /**
     * Check if the file extension matches the specified file type
     *
     * @param string $type file type to compare against the current file extension
     * @return bool Returns true if the file's extension matches the specified file type, false otherwise.
     */
    public function checkType(string $type): bool
    {
        return $this->checkTypeIn([$type]);
    }

    /**
     * Check if the file extension matches the specified file types
     *
     * @param array $types file type to compare against the current file extension
     * @return bool Returns true if the file's extension matches any of the specified types, false otherwise.
     */
    public function checkTypeIn(array $types): bool
    {
        $checks = [];

        foreach ($types as $type) {

            $type = strtolower($type);

            if (str_starts_with($type, ".")) {
                $checks[] = substr($type, 1);
            } else {
                $checks[] = $type;
            }
        }

        return in_array($this->extension(), $checks);
    }
}
