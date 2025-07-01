<?php

namespace NovaFrame\View;
use NovaFrame\Container\Container;
use NovaFrame\View\Exceptions\InvalidEngine;
use NovaFrame\View\Exceptions\UnknownSection;

class Renderer
{
    /**
     * The template to extend
     *
     * @var string
     */
    private string $extends = '';

    /**
     * Stores content for each named section
     *
     * @var array<string, string>
     */
    private array $sections = [];

    /**
     * Stack of currently opened section names
     *
     * @var string[]
     */
    private array $sectionStacks = [];

    /**
     * Temporary data available to templates during rendering
     *
     * @var array
     */
    private array $tmpData = [];

    /**
     * @param string|null $path Base path to views directory.
     * @param string|object|null $engine Optional third-party rendering engine instance or class name.
     * @param array $options Options for the rendering engine.
     *
     * @throws InvalidEngine When the specified engine class does not exist.
     */
    public function __construct(
        private ?string $path = null,
        private null|string|object $engine = null,
        private readonly array $options = [] // options for third party renderer engine
    )
    {
        if (empty($this->path)) {
            $this->path = DIR_APP . 'Views';
        }

        $this->path = $this->normalizePath($this->path);
        $this->path = rtrim($this->path, DS);

        if (!empty($this->engine)) {
            $this->engine = $this->validateEngine($this->engine);
        }
    }

    /**
     * Validate and instantiate the rendering engine if needed.
     *
     * @param string|object $engine Class name or engine instance.
     * @return object Engine instance.
     *
     * @throws InvalidEngine When class does not exist.
     */
    private function validateEngine(string|object $engine)
    {
        if (is_string($engine)) {
            if (!class_exists($engine)) {
                throw new InvalidEngine($engine);
            }

            return (new Container())->get($engine, $this->options['parameters']);
        }

        return $engine;
    }

    /**
     * Render one or more templates with given data.
     *
     * @param string|array $template Template name(s) to render.
     * @param array $data Variables to extract into the template scope.
     * @return string Rendered HTML/content.
     */
    public function render(string|array $template, array $data = []): string
    {
        $templates = $this->resolveTemplate($template);

        if ($this->engine && method_exists($this->engine, 'render')) {
            return $this->engine->render($this->options['resolveTemplate'] ? $templates : $template, $data);
        }

        $output = $this->renderTemplate($templates, $data);

        if ($this->extends) {
            $extendTemplate = new self($this->path, $this->engine, $this->options);
            $extendTemplate->sections = $this->sections;
            $extended = $extendTemplate->render($this->extends, $data);

            $output = count($templates) > 1 ? $output . $extended : $extended;
        }

        return $output;
    }

    /**
     * Set a template to extend (inherit).
     *
     * @param string $template Template name to extend.
     */
    public function extends(string $template): void
    {
        $this->extends = $this->normalizePath($template);
    }

    /**
     * Start capturing a section's content.
     *
     * @param string $section Name of the section.
     */
    public function section(string $section): void
    {
        $this->sectionStacks[] = $section;

        if (!isset($this->sections[$section])) {
            $this->sections[$section] = '';
        }

        ob_start();
    }

    /**
     * End the current section capture and store its content.
     *
     * @throws UnknownSection When no section was started.
     */
    public function end(): void
    {
        if (empty($this->sectionStacks)) {
            throw new UnknownSection();
        }

        $section = array_pop($this->sectionStacks);

        $content = ob_get_clean();

        $this->sections[$section] .= $content;
    }

    /**
     * Output the content of a named section.
     *
     * @param string $section Section name.
     */
    public function yield(string $section): void
    {
        echo $this->sections[$section] ?? '';
    }

    /**
     * Render the PHP templates by including them with the provided data extracted.
     *
     * @param string[] $templates List of template file paths.
     * @param array $data Data variables for templates.
     * @return string Captured output.
     */
    private function renderTemplate(array $templates, array $data = []): string
    {
        $this->tmpData = $data;
        unset($data);

        ob_start();

        if (!empty($this->tmpData)) {
            extract($this->tmpData, EXTR_SKIP);
        }

        foreach ($templates as $template) {
            include $template;
        }

        return ob_get_clean();
    }

    /**
     * Resolve template names to full paths using Template::resolve().
     *
     * @param string|array $templates
     * @return string[] Resolved full file paths.
     */
    private function resolveTemplate(string|array $templates): array
    {
        return Template::resolve($this->path, $templates);
    }

    /**
     * Normalize path by replacing directory separators and dots with system DS.
     *
     * @param string $path
     * @return string Normalized path.
     */
    private function normalizePath(string $path): string
    {
        return str_replace(['\\', '/', '.'], DS, $path);
    }
}
