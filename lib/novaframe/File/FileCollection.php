<?php

namespace Nova\File;

use InvalidArgumentException;

class FileCollection
{
    /**
     * @var array An array holding file paths in the collection.
     */
    private array $files;

    /**
     * File collection constructor
     *
     * @param array|string|null $files
     */
    public function __construct(array|string|null $files = [])
    {
        if (empty($files)) {
            $this->files = [];
        } elseif (is_array($files)) {
            $this->files = $files;
        } else {
            $this->files[] = $files;
        }
    }

    /**
     * Adds files to the collection, merging with existing files and ensuring uniqueness.
     *
     * @param array|string $files A file path or an array of file paths to add to the collection.
     * @return FileCollection The updated FileCollection instance.
     * @throws InvalidArgumentException If $files is an empty string or array.
     */
    public function push(array|string $files): FileCollection
    {
        if (empty($files)) {
            throw new InvalidArgumentException("You cannot set empty string (or) array");
        }

        if (is_array($files)) {
            $this->files = array_merge($this->files, $files);
        } else {
            $this->files[] = $files;
        }

        $this->files = array_unique($this->files);

        return $this;
    }

    /**
     * Populates the collection with files from a specified directory.
     *
     * @param string $dir The directory path to get files from.
     * @return FileCollection The updated FileCollection instance.
     * @throws InvalidArgumentException If the directory is empty.
     */
    public function from(string $dir): FileCollection
    {
        if (empty($dir)) {
            throw new InvalidArgumentException("Directory cannot be empty");
        }

        $this->push(glob($dir));

        return $this;
    }

    /**
     * Removes all files or specified files from the collection.
     *
     * @param string|array $files A file path or an array of file paths to remove from the collection.
     * @return FileCollection The updated FileCollection instance.
     */
    public function remove(string|array $files): FileCollection
    {
        if (empty($files)) {
            $this->files = [];

            return $this;
        }

        return $this->filter(function ($file) use ($files) {
            if (is_array($files)) {
                return !in_array($file, $files);
            } else {
                return $file !== $files;
            }
        });
    }

    /**
     * Converts the collection to an array.
     *
     * Returns the files in the collection as an array.
     *
     * @return array The array of files in the collection.
     */
    public function toArray(): array
    {
        return $this->files;
    }

    /**
     * Converts the collection to an object.
     *
     * Returns the files in the collection as an object with a `files` property containing the array of files.
     *
     * @return object An object with a `files` property containing the array of files.
     */
    public function toObject(): object
    {
        $files['files'] = $this->files;

        return (object) $files;
    }

    /**
     * Filter files in the collection.
     *
     * Applies a callback function to each file in the collection and returns a new collection or array
     * based on the `$update` parameter. If `$update` is true, the current collection is updated with the filtered results.
     *
     * @param callable $callback The callback function to apply to each file.
     * @param int $mode Optional. The filter mode. Default is 0.
     * @param bool $update Optional. If true, update the current collection. Default is false.
     * @return FileCollection|array The filtered FileCollection instance or array, depending on `$update`.
     */
    public function filter(callable $callback, int $mode = 0, bool $update = false): FileCollection|array
    {
        return $this->filterAction($callback, $mode, $update);
    }

    /**
     * Applies a callback function to filter files in the collection.
     *
     * This method filters the files in the collection based on the provided callback function. If `$update` is true,
     * the current collection is updated with the filtered results. Otherwise, a new array of filtered files is returned.
     *
     * @param callable $callback The callback function to apply to each file.
     * @param int $mode Optional. The filter mode. Default is 0.
     * @param bool $update Optional. If true, updates the current collection with the filtered results. Default is false.
     * @return FileCollection|array The filtered FileCollection instance if `$update` is true, otherwise an array of filtered files.
     */
    private function filterAction(callable $callback, int $mode = 0, bool $update = false): FileCollection|array
    {
        $array = array_filter($this->files, $callback, $mode);

        if ($update) {
            $this->files = $array;

            return $this;
        }

        return $array;
    }

    /**
     * Applies a callback function to each file in the collection.
     *
     * Iterates through each file in the collection and applies the provided callback function.
     * This does not modify the collection but allows for performing operations on each file.
     *
     * @param callable $callback The callback function to apply to each file.
     * @return FileCollection Returns the current instance for method chaining.
     */
    public function each(callable $callback): FileCollection
    {
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $callback($file);
            }
        }

        return $this;
    }

    /**
     * Includes each file in the collection if it exists.
     *
     * @return void
     */
    public function include(): void
    {
        $this->each(fn ($file) => f($file)->includeWhen(
            fn() => f($file)->exists())
        );
    }

    /**
     * Deletes each file in the collection.
     *
     * @return FileCollection The updated FileCollection instance.
     */
    public function unlink(): FileCollection
    {
        $this->each(fn($file) => unlink($file));

        return $this;
    }

    /**
     * Get all files in the collection
     *
     * @return array
     */
    public function get(): array
    {
        return $this->files;
    }

    /**
     * Retrieves a File instance for a specific file in the collection.
     *
     * Checks if the specified file exists in the collection and returns a `File` instance for it.
     * If the file does not exist in the collection, it returns null.
     *
     * @param string $file The file path to retrieve.
     * @return File|null A File instance if the file exists in the collection; otherwise, null.
     */
    public function file(string $file): ?File
    {
        if (in_array($file, $this->files)) {
            return f($file);
        }

        return null;
    }

    /**
     * Sorts the files in the collection.
     *
     * Applies the provided callback function to sort the files in the collection.
     *
     * @param callable $callback The callback function to use for sorting.
     * @return FileCollection Returns the current instance for method chaining.
     */
    public function sort(callable $callback): FileCollection
    {
        usort($this->files, $callback);

        return $this;
    }

    /**
     * Finds files in the collection that match the given callback function.
     *
     * Applies the callback function to filter files in the collection based on a condition.
     * Returns an array of files that match the condition.
     *
     * @param callable $callback The callback function to apply for finding files.
     * @return array An array of files that match the callback condition.
     */
    public function find(callable $callback): array
    {
        return $this->filterAction($callback);
    }

    /**
     * Get the number of files in the collection.
     *
     * Returns the count of files currently in the collection.
     *
     * @return int The number of files in the collection.
     */
    public function count(): int
    {
        return count($this->files);
    }

    /**
     * Check if a specific file is in the collection.
     *
     * Checks if the specified file path exists in the collection.
     *
     * @param string $file The file path to check.
     * @return bool True if the file exists in the collection; otherwise, false.
     */
    public function has(string $file): bool
    {
        return in_array($file, $this->files);
    }

    /**
     * Merge another FileCollection into the current collection.
     *
     * Adds files from another FileCollection instance to the current collection, ensuring uniqueness.
     *
     * @param FileCollection $collection The FileCollection instance to merge.
     * @return FileCollection Returns the updated FileCollection instance.
     */
    public function merge(FileCollection $collection): FileCollection
    {
        $this->push($collection->files);

        return $this;
    }

    /**
     * Group the files by a specified attribute.
     *
     * @param string $by The attribute to group files by. Supported values are:
     *                   - "extension": groups by file extension.
     *                   - "path": groups by file directory path.
     *                   Default is "extension".
     *
     * @return array An associative array where the keys are the values of the specified attribute
     *               (e.g., file extensions or directory paths) and the values are arrays of files
     *               associated with each attribute.
     */
    public function grouped(string $by = "extension"): array
    {
        $grouped = [];

        $this->each(function ($file) use (&$grouped, $by) {
            switch ($by) {
                case "extension":
                    $grouped[f($file)->extension()][] = $file;

                    break;

                case "path":
                    $grouped[f($file)->dirname()][] = $file;

                    break;

                default:
                    $grouped[] = $file;
            }
        });

        return $grouped;
    }

    /**
     * Order the files by their size.
     *
     * @param string $mode The mode to order the files. Supported values are:
     *                     - "asc": orders by file size in ascending order.
     *                     - "desc": orders by file size in descending order.
     *                     Default is "asc".
     * @param bool $filesOnly If true, returns a flat array of files sorted by size.
     *                        If false, returns an associative array where the keys are file sizes
     *                        and the values are either arrays of files or a single file based on the number of files of that size.
     *
     * @return array An ordered array of files. The structure of the returned array depends on the $filesOnly flag:
     *               - If $filesOnly is true, returns a flat array of files sorted by size.
     *               - If $filesOnly is false, returns an associative array with file sizes as keys and arrays or single files as values.
     *
     * @throws \InvalidArgumentException If an unsupported order mode is provided.
     */
    public function order(string $mode = "asc", bool $filesOnly = false): array
    {
        $ordered = [];

        $this->each(function ($file) use (&$ordered) {
            $ordered[f($file)->size()][] = $file;
        });

        switch ($mode) {
            case "asc":
                ksort($ordered, SORT_NUMERIC);

                break;

            case "desc":
                krsort($ordered, SORT_NUMERIC);

                break;

            default:
                throw new InvalidArgumentException("Unsupported order mode $mode is used");
        }

        $result = [];

        foreach ($ordered as $size => $files) {
            if ($filesOnly) {
                $result = array_merge($result, $files);
            } else {
                if (count($files) > 1) {
                    $result[$size] = $files;
                } else {
                    $result[$size] = $files[0];
                }
            }
        }

        return $result;
    }
}
