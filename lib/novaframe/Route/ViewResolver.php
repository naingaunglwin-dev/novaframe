<?php

namespace Nova\Route;

class ViewResolver
{
    /**
     * The base path where views are located.
     *
     * @var string
     */
    private string $path;

    /**
     * The name of the view file.
     *
     * @var string
     */
    private string $view;

    /**
     * ViewResolver constructor.
     *
     * Initializes a new instance of the ViewResolver class with the given path and view name.
     *
     * @param string $path The base path where views are located.
     * @param string $view The name of the view file.
     */
    public function __construct(string $path, string $view)
    {
        $this->path = $path;
        $this->view = $view;
    }

    /**
     * Resolves the path to the view file.
     *
     * This method constructs the full path to the view file based on the provided path and view name.
     * If the view file does not exist, it invokes the provided view exception handler.
     *
     * @param callable $viewException The callable function to handle view file not found exception.
     * @return string The resolved path to the view file.
     */
    public function resolve(callable $viewException): string
    {
        if (
            !str_ends_with($this->path, DIRECTORY_SEPARATOR)
            && !str_starts_with($this->view, DIRECTORY_SEPARATOR)
        ) {
            $file = $this->path . DIRECTORY_SEPARATOR . $this->view;
        } elseif (
            str_ends_with($this->path, DIRECTORY_SEPARATOR)
            && str_starts_with($this->view, DIRECTORY_SEPARATOR)
        ) {
            $this->path = substr($this->path, 0, -1);
            $file = $this->path . $this->view;
        } else {
            $file = $this->path . $this->view;
        }

        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if (empty($extension)) {
            $file = $file . '.php';
        }

        if (!file_exists($file)) {
            call_user_func($viewException, $file);
            exit(0);
        }

        return $file;
    }
}
