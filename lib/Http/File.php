<?php

namespace NovaFrame\Http;

use NovaFrame\Storage\Storage;

class File
{
    private ?Storage $storage;

    /**
     * File Constructor
     *
     * @param array $uploaded The $_FILES-like array for a single uploaded file.
     *                        Expected keys: name, size, type, tmp_name, error.
     */
    public function __construct(private readonly array $uploaded)
    {
    }

    /**
     * Get the original name of the uploaded file.
     *
     * @return string The file name.
     */
    public function getName(): string
    {
        return $this->uploaded['name'];
    }

    /**
     * Get the file size in bytes.
     *
     * @return int The file size.
     */
    public function getSize(): int
    {
        return $this->uploaded['size'];
    }


    /**
     * Get the MIME type of the uploaded file.
     *
     * @return string The MIME type.
     */
    public function getMimeType(): string
    {
        return $this->uploaded['type'];
    }

    /**
     * Get the temporary file path on the server.
     *
     * @return string Temporary file path.
     */
    public function getTmpName(): string
    {
        return $this->uploaded['tmp_name'];
    }


    /**
     * Get the upload error code.
     *
     * @return int Upload error code (UPLOAD_ERR_* constants)
     */
    public function getError(): int
    {
        return $this->uploaded['error'];
    }

    /**
     * Check if the uploaded file has any error.
     *
     * @return bool True if there is an error, false if upload was successful.
     */
    public function hasError(): bool
    {
        $errors = $this->getError();

        return $errors !== UPLOAD_ERR_OK;
    }

    /**
     * Get the original uploaded file data as an array.
     *
     * @return array The original uploaded file array.
     */
    public function toArray(): array
    {
        return $this->uploaded;
    }

    /**
     * Move the uploaded file to permanent storage.
     *
     * @param string|null $to Optional destination directory.
     *                        If not provided, uses the framework's default storage directory.
     *
     * @return string|false Returns the file path on success, or false on failure.
     */
    public function save(?string $to = null): string|false
    {
        if (!isset($this->storage)) {
            $this->storage = new Storage(DIR_STORAGE . 'public' . DS . 'upload');
        }

        return $this->storage->saveUploadedFile($this, $to);
    }
}
