<?php

namespace NovaFrame\Storage;

use NovaFrame\Helpers\FileSystem\FileSystem;
use NovaFrame\Http\File;

class Storage
{
    /**
     * Storage constructor.
     *
     * @param string $destination Default directory where files will be stored.
     */
    public function __construct(
        private string $destination
    )
    {
        FileSystem::mkdir($this->destination, 0777, true);
    }

    /**
     * Save a file from a source path to the destination directory.
     *
     * @param string $source      Absolute path of the source file.
     * @param string|null $name   Optional custom name for the file (defaults to original basename).
     * @param string|null $destination Optional target directory (defaults to configured destination).
     *
     * @return bool|null True if saved, false on failure, null if source doesn't exist.
     */
    public function save(string $source, ?string $name = null, ?string $destination = null)
    {
        $destination ??= $this->destination;

        if (!file_exists($source)) {
            return null;
        }

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $filename = $name ?? basename($source);

        $target = rtrim($destination, DS) . DS . $filename;

        if (copy($source, $target)) {
            return true;
        }

        return false;
    }

    /**
     * Saves an uploaded file to the specified destination.
     *
     * Handles a single file upload. It accepts either a `File` object directly,
     * or an input name (string) along with a `Request` object to retrieve the file.
     * The destination directory is created if it doesn't already exist.
     *
     * @param File $file        A `File` object.
     * @param string|null $destination Optional target directory; defaults to the configured storage path.
     *
     * @return string|false Returns the saved file path as a string on success, or false on failure.
     */
    public function saveUploadedFile(File $file, ?string $destination = null): bool|string
    {
        $destination ??= $this->destination;

        FileSystem::mkdir($destination, 0777, true);

        if (!$file->hasError()) {
            $filename = basename($file->getName());
            $target = rtrim($destination, DS) . DS . $filename;

            if (move_uploaded_file($file->getTmpName(), $target)) {
                return $target;
            }
        }

        return false;
    }
}
