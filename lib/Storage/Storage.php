<?php

namespace NovaFrame\Storage;

use NovaFrame\Helpers\FileSystem\FileSystem;
use NovaFrame\Http\Request;

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
     * Save an uploaded file from $_FILES array or from a Request object.
     *
     * @param array|string $name If array, assumes full $_FILES structure. If string, a Request object is required.
     * @param Request|null $request Optional request object to retrieve file from.
     * @param string|null $destination Optional target directory (defaults to configured destination).
     *
     * @return bool True on success, false on failure.
     *
     * @throws \BadMethodCallException If a string is passed as $name without a Request object.
     */
    public function saveUploadedFile(array|string $name, ?Request $request = null, ?string $destination = null)
    {
        $destination ??= $this->destination;

        FileSystem::mkdir($destination, 0777, true);

        if (is_string($name) && empty($request)) {
            throw new \BadMethodCallException('Request object need to pass if first parameter is a string');
        }

        if (is_array($name)) {
            $uploaded = $name;
        } else {
            $uploaded = $request->file($name);
        }

        if (isset($uploaded['error']) && $uploaded['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $name = basename($uploaded['name']);
        $target = rtrim($destination, DS) . DS . $name;

        if (move_uploaded_file($uploaded['tmp_name'], $target)) {
            return true;
        }

        return false;
    }
}
