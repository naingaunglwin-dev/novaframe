<?php

namespace Nova\View;

use BadMethodCallException;
use Nova\Exception\Exceptions\FileException;

class View
{
    /**
     * The base path for views.
     *
     * @var string
     */
    private string $path;

    /**
     * An array containing the paths to individual views.
     *
     * @var array
     */
    private array $views = [];

    /**
     * An array containing temporary views used during rendering.
     *
     * @var array
     */
    private array $temp = [];

    /**
     * An associative array containing sections and their content.
     *
     * @var array
     */
    private static array $sections = [];

    /**
     * The path to the view being extended.
     *
     * @var string
     */
    private static string $extend = '';

    /**
     * An array containing the views to be rendered.
     *
     * @var array
     */
    private array $render = [];

    /**
     * A flag indicating whether a section is being rendered.
     *
     * @var bool
     */
    private bool $isSection = false;

    public function __construct(string $path = null)
    {
        $path = trim($path ?? '');

        // Check if $path is not empty and end with '/' or not
        if ($path !== '' && !empty($path) && !str_ends_with($path, DIRECTORY_SEPARATOR)) {
            $path = $path . DIRECTORY_SEPARATOR;
        }

        // Get view path from config if user doesn't pass the view path
        $pathFromConfig = trim(config('view.paths.view') ?? '');

        // Check if $path is empty and config path end with '/' or not
        if (empty($path) && !str_ends_with($pathFromConfig, DIRECTORY_SEPARATOR)) {
            $pathFromConfig = $pathFromConfig . DIRECTORY_SEPARATOR;
        }

        $this->path = $path !== '' ? $path : $pathFromConfig;

        $this->path = str_replace("/", "\\", $this->path);
    }

    /**
     * Sets the views or views to be rendered.
     *
     * @param string|array $view The path to a single views or an array of views paths.
     *
     * @throws BadMethodCallException
     */
    private function setView(string|array $view): void
    {
        $files = [];

        if (is_string($view)) {
            $files[] = $view;
        } else {
            $files = $view;
        }

        $filtered_views = [];

        foreach ($files as $file) {
            $file = trim($file);

            if (str_starts_with($file, '*')) {
                $file = substr($file, 1);
            }

            $file = str_replace('*', '\\', $file);

            $file = str_replace("/", "\\", $file);

            $file = str_replace($this->path, '', $file);

            $file = $this->verifyExtension($this->path . $file);

            if (!$this->isExists($file)) {
                throw FileException::pathNotFound($file);
            }

            $filtered_views[] = $file;
        }

        $this->views = $filtered_views;
    }

    /**
     * Renders and captures the output of the specified views.
     *
     * @param array $views The path to an array of views paths.
     * @param array|null $data  An associative array of data to be passed to the views(s).
     *
     * @return string|bool The rendered views(s) output, or false on failure.
     */
    private function getViews(array $views, ?array $data): bool|string
    {
        $temps = [];

        foreach ($views as $tmp) {
            $tmp = trim($tmp);

            $tmp = str_replace('*', '/', $tmp);

            $tmp = str_replace('/', '\\', $tmp);

            $temps[] = $tmp;
        }

        // Save views in local properties
        // to prevent variable name conflict from $data when extract
        $this->temp = $temps;

        if ($data !== null) {
            extract($data, EXTR_SKIP);
        }

        ob_start();

        foreach ($this->temp as $view) {
            include $view;
        }

        $content = ob_get_clean();

        // Clear the temp views
        $this->temp = [];

        return $content;
    }

    /**
     * Renders the specified view(s).
     *
     * @param string|array $view   The path to a single view or an array of view paths.
     * @param array|null   $data   An associative array of data to be passed to the view(s).
     * @param bool         $return Whether to return the rendered view(s) as a string.
     *
     * @return string|null The rendered view(s), or null if $return is true.
     */
    public function render(string|array $view, array $data = null, bool $return = false): ?string
    {
        //var_dump($views);
        $this->setView($view);

        // Prepare views to render
        $this->prepare();

        // Get included views
        $result = $this->getViews($this->render, $data);

        if ($this->isSection && !empty(self::$sections)) {
            // Render the sections
            $this->isSection = false;

            // Re-prepare the views in case sections are extended
            $this->prepare();

            // Get included views
            $result = $this->getViews($this->render, $data);
        }

        if ($return === false) {
            // echo the output if return false
            echo $result;
        }

        return $result;
    }

    /**
     * Prepares the views for rendering.
     *
     * @return void
     */
    private function prepare(): void
    {
        $files = [];

        foreach ($this->views as $view) {
            $content = file_get_contents($view);

            if ($content !== false && preg_match('/\$this->extends\([\'"]?(.*?)[\'"]?\)/s', $content, $matches)) {
                $files[] = $this->verifyExtension($this->path . $matches[1]);
            }
            $files[] = $view;
        }

        $this->render = $files;

        $this->render = array_unique($this->render);
    }

    /**
     * Starts a new section.
     *
     * @param string $name The name of the section.
     *
     * @throws BadMethodCallException
     */
    public function section(string $name): void
    {
        if (empty(self::$extend)) {
            throw new BadMethodCallException("No extend method defined");
        }

        $this->isSection = true;

        self::$sections[$name] = [
            'content' => '',
            'extend'  => self::$extend,
        ];

        ob_start();
    }

    /**
     * Ends a section and captures its content.
     *
     * @param string $name The name of the section.
     *
     * @throws BadMethodCallException
     */
    public function endSection(string $name): void
    {
        if (!isset(self::$sections[$name])) {
            throw new BadMethodCallException('Section "' . $name . '" does not exist');
        }

        $content = ob_get_clean();

        self::$sections[$name]['content'] = $content;
    }

    /**
     * Sets the view to be extended.
     *
     * @param string $file The path to the view file being extended.
     *
     * @throws BadMethodCallException
     */
    public function extends(string $file): void
    {
        $file = trim($file);
        $file = str_replace('*', '/', $file);

        $file = $this->verifyExtension($this->path . $file);

        if (!$this->isExists($file)) {
            throw FileException::pathNotFound($file);
        }

        self::$extend = $file;
    }

    /**
     * Displays the content of a section.
     *
     * @param string $name The name of the section.
     *
     * @return string|null The content of the section, or null if the section does not exist.
     */
    public function displaySection(string $name): ?string
    {
        if (isset(self::$sections[$name])) {
            echo self::$sections[$name]['content'];
            return '';
        }

        return null;
    }

    /**
     * Verifies whether a views file exists.
     *
     * @param string $view The path to the views file.
     *
     * @return bool True if the views file exists, false otherwise.
     */
    private function isExists(string $view): bool
    {
        return file_exists($view);
    }

    /**
     * Verifies and adds the appropriate file extension to a views file.
     *
     * @param string $file The path to the views file.
     *
     * @return string The path to the views file with the appropriate extension.
     */
    private function verifyExtension(string $file): string
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if (empty($extension)) {
            $file = $file . '.php';
        }

        return $file;
    }

    /**
     * Retrieves an instance of the View class.
     *
     * @param bool $outputOnly To determine whether to clean only output buffer or need to clean view
     *
     * @return void
     */
    public function clean(bool $outputOnly = true): void
    {
        self::$extend   = '';
        self::$sections = [];

        if (!$outputOnly) {
            $this->views    = [];
        }
    }
}
