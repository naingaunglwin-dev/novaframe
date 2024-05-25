<?php

use Nova\Helpers\Modules\File;

if (!function_exists('__required')) {
    /**
     * Includes the given files if it exists
     *
     * @param string ...$files Files to includes
     * @return void
     */
    function required(string ...$files): void
    {
        $files = func_get_args();

        if (!empty($files)) {
            foreach ($files as $file) {
                $file = new File($file);
                if ($file->isExist()) {
                    require_once $file->getFile();
                }
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
     * This function generates an HTML link tag to include a CSS file. It also appends a version parameter for cache busting.
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

        if (!_file(PUBLIC_PATH . $cssFile)->isExist()) {
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
     * This function generates an HTML script tag to include a JavaScript file. It also appends a version parameter for cache busting.
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

        if (!_file(PUBLIC_PATH . $jsFile)->isExist()) {
            throw new InvalidArgumentException('File not found ' . $jsFile);
        }

        $html .= baseUrl($jsFile);

        $html .= '?' . config('app.asset_version') . '"></script>';

        echo $html;
    }
}

if (!function_exists('_file')) {
    /**
     * Create a new instance of the File class.
     *
     * This function returns a new instance of the File class, allowing you to work with file paths
     * in a convenient and streamlined manner. You can chain method calls on the returned object
     * for operations like setting the file path and retrieving file information.
     *
     * Example Usage:
     * ```php
     * _file()->setFile('file.ext')->getName();
     * ```
     *
     * @param string|null $file File name
     *
     * @return File A new instance of the File class.
     */
    function _file(string $file = null): File
    {
        return new File($file);
    }
}
