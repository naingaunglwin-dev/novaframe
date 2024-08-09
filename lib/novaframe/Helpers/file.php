<?php

use Nova\File\File;

if (!function_exists('inc')) {
    /**
     * Includes the given files if it exists
     *
     * @param string ...$files Files to includes
     * @return void
     */
    function inc(string ...$files): void
    {
        if (!empty($files = func_get_args())) {

            $files = fc($files);

            if (env("APP_ENVIRONMENT", "production") === "production") {
                $files->each(function ($file) {
                    $file = f($file, true);
                    $file->includeWhen(function () use ($file) {
                        return $file->exists();
                    });
                });
            } else {
                $files->include();
            }
        }
    }
}

if (!function_exists('view')) {
    /**
     * Renders the specified view(s).
     *
     * @param string|array $view The path to a single view or an array of view paths.
     * @param array|null $data An associative array of data to be passed to the view(s).
     * @param bool $return Whether to return the rendered view(s) as a string.
     *
     * @return string|null The rendered view(s), or null if $return is true.
     */
    function view(string|array $view, array $data = null, bool $return = false): ?string
    {
        $class = new Nova\View\View(config('view.paths.view') ?? '');

        return $class->render($view, $data, $return);
    }
}

if (!function_exists('css')) {
    /**
     * Generate an HTML link tag for a CSS file.
     *
     * This generates an HTML link tag to include a CSS file. It also appends a version parameter for cache busting.
     *
     * @param string $file The name of the CSS file.
     * @param string $path The path to the CSS file relative to the public directory. Default is 'css'.
     * @return void
     *
     * @throws InvalidArgumentException If the CSS file is not found.
     */
    function css(string $file, string $path = 'css'): void
    {
        $html = '<link rel="stylesheet" href="';

        $cssFile = str_ends_with($path, '/') ? $path . $file : $path . '/' . $file . '.css';

        if (!f(PUBLIC_PATH . $cssFile)->exists()) {
            throw new InvalidArgumentException('File not found ' . $cssFile);
        }

        $html .= baseUrl($cssFile);

        $html .= '?' . config('app.asset_version') . '" />';

        echo $html;
    }
}

if (!function_exists('js')) {
    /**
     * Generate an HTML script tag for a JavaScript file.
     *
     * This generates an HTML script tag to include a JavaScript file. It also appends a version parameter for cache busting.
     *
     * @param string $file The name of the JavaScript file.
     * @param string $path The path to the JavaScript file relative to the public directory. Default is 'js'.
     * @return void
     *
     * @throws InvalidArgumentException If the JavaScript file is not found.
     */
    function js(string $file, string $path = 'js'): void
    {
        $html = '<script src="';

        $jsFile = str_ends_with($path, '/') ? $path . $file : $path . '/' . $file . '.js';

        if (!f(PUBLIC_PATH . $jsFile)->exists()) {
            throw new InvalidArgumentException('File not found ' . $jsFile);
        }

        $html .= baseUrl($jsFile);

        $html .= '?' . config('app.asset_version') . '"></script>';

        echo $html;
    }
}

if (!function_exists('f')) {
    /**
     * Create a new instance of the File class.
     *
     * This returns a new instance of the File class, allowing you to work with file paths
     * in a convenient and streamlined manner. You can chain method calls on the returned object
     * for operations like setting the file path and retrieving file information.
     *
     * Example Usage:
     *
     * ```
     * f('file.ext')->extension();
     *
     * f()->set('file.ext')->name();
     * ```
     *
     * @param string|null $file File name
     * @param bool $strict Strict mode for file
     *
     * @return \Nova\File\File A new instance of the File class.
     */
    function f(string $file = null, bool $strict = false): \Nova\File\File
    {
        return new \Nova\File\File($file, $strict);
    }
}

if (!function_exists("fc")) {
    /**
     * Creates a new instance of the FileCollection class with the provided files.
     *
     * This function acts as a shorthand to initialize a FileCollection object, optionally with a set of files.
     *
     * @param array|string|null $files Optional. An array of file paths or a single file path to initialize the collection with. Defaults to null.
     * @return \Nova\File\FileCollection A new instance of the FileCollection class.
     */
    function fc(array|string|null $files = null): \Nova\File\FileCollection
    {
        return new \Nova\File\FileCollection($files);
    }
}
