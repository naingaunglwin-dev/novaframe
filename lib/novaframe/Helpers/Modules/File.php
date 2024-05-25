<?php

namespace Nova\Helpers\Modules;

class File
{
    /**
     * @var string File
     */
    private string $file;

    public function __construct(string $file = null)
    {
        $this->file = $file ?? '';
    }

    /**
     * Set the file path.
     *
     * @param string $file The file path.
     * @return File
     */
    public function setFile(string $file): File
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFile(): string|null
    {
        return $this->file ?? null;
    }

    /**
     * Get the name of the file (without the extension).
     *
     * @return string|null The name of the file, or null if the file path is empty.
     */
    public function getName(): string|null
    {
        return $this->ReturnFile('name');
    }

    /**
     * Get the file extension.
     *
     * @return string|null The file extension, or null if the file path is empty.
     */
    public function getExtension(string $case = 'lower'): string|null
    {
        $result = $this->ReturnFile('extension');

        if (empty($result)) {
            return null;
        }

        return match (strtolower($case)) {
            'lower' => strtolower($result),
            'upper' => strtoupper($result),
            default => $result,
        };
    }

    /**
     * Get the base name of the file (including the extension).
     *
     * @return string|null The base name of the file, or null if the file path is empty.
     */
    public function getBaseName(): string|null
    {
        return $this->ReturnFile('base_name');
    }

    /**
     * Get an array containing information about the file path.
     *
     * @return array|null An associative array with keys like 'dirname', 'basename', 'extension', and 'filename',
     *                    or null if the file path is empty.
     */
    public function getAll(): array|null
    {
        return $this->ReturnFile('all');
    }

    /**
     * Check if the file exists.
     *
     * @return bool True if the file exists, false otherwise or if the file path is empty.
     */
    public function isExist(): bool
    {
        if (empty($this->file)) return false;

        return file_exists($this->file);
    }

    /**
     * Get the file size
     *
     * @return string|null
     */
    public function getSize(): string|null
    {
        return $this->ReturnFile('size');
    }

    /**
     * Write content to a file.
     *
     * @param string $content The content to write to the file.
     * @param bool $checkExists Optional. Whether to check if the file exists before writing.
     * @param bool $overwrite Optional. If true and the file exists, overwrites its content.
     *
     * @return int|false Returns the number of bytes written on success, false on failure.
     *                 If $checkExists is true and $overwrite is false, returns false if the file doesn't exist.
     */
    public function writeContent(string $content, bool $checkExists = false, bool $overwrite = false): bool|int
    {
        if ($checkExists === true) {
            if ($overwrite === true && $this->isExist()) {
                $originalContent = $this->getContent();

                return $this->writeContent($originalContent . $content);
            } else if (!$this->isExist()) {
                return false;
            }
        }

        return file_put_contents($this->file, $content);
    }

    /**
     * Get the file content
     *
     * @return bool|string|null
     */
    public function getContent(): bool|string|null
    {
        return $this->isExist() ? file_get_contents($this->file) : null;
    }

    /**
     * Required the given file
     *
     * @return mixed|null
     */
    public function required(): mixed
    {
        if ($this->isExist()) {
            return require_once $this->file;
        } else {
            return null;
        }
    }

    /**
     * Return information about the file based on the specified type.
     *
     * @param string $type PathInfo type to return ('name', 'extension', 'base_name', 'all', 'size', etc.).
     * @return array|string|null Information about the file, or null if the file path is empty.
     */
    private function ReturnFile(string $type): array|string|null
    {
        if (empty($this->file)) return null;

        return match ($type) {
            'name'      => pathinfo($this->file, PATHINFO_FILENAME),
            'base_name' => pathinfo($this->file, PATHINFO_BASENAME),
            'extension' => pathinfo($this->file, PATHINFO_EXTENSION),
            'dir'       => pathinfo($this->file, PATHINFO_DIRNAME),
            'size'      => filesize($this->file),
            default     => pathinfo($this->file),
        };
    }

    /**
     * Check if the file's extension matches the specified file types.
     *
     * @param string|array $toCheck File types to compare against the current file's extension.
     *                              Accepts either a string or an array of file types.
     *                              If a string, it can be a single file type or multiple file types separated by commas.
     *                              For example, 'jpg', 'png', or '.jpg', '.png'.
     *
     * @return bool Returns true if the file's extension matches any of the specified types, false otherwise.
     */
    public function checkFileType(string|array $toCheck): bool
    {
        $checkTypes = [];

        if (is_string($toCheck)) {
            $checkTypes[] = $toCheck;
        } else {
            $checkTypes = $toCheck;
        }

        $types = [];
        foreach ($checkTypes as $checkType) {
            if (str_starts_with($checkType, '.')) {
                $types[] = substr($checkType, 1);
            } else {
                $types[] = $checkType;
            }
        }

        if (in_array($this->getExtension(), $types)) {
            return true;
        }

        return false;
    }
}
