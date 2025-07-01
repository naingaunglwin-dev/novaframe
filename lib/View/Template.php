<?php

namespace NovaFrame\View;

use NovaFrame\View\Exceptions\TemplateNotFound;
use NovaFrame\View\Exceptions\TemplateNotReadable;

class Template
{
    /**
     * Resolve one or more template names into absolute file paths.
     *
     * - Normalizes template paths,
     * - Adds `.php` extension if none present,
     * - Ensures the resolved path is inside the base $path directory,
     * - Throws exceptions or returns false on failure based on $throwException.
     *
     * @param string $path Base directory where templates are located.
     * @param string|array $templates Template name or list of template names.
     * @param bool $throwException Whether to throw exceptions if template not found or unreadable. Default true.
     * @return array|bool Array of resolved template absolute paths or false on failure if $throwException is false.
     *
     * @throws TemplateNotFound When a template file is not found and $throwException is true.
     * @throws TemplateNotReadable When a template file is not readable and $throwException is true.
     */
    public static function resolve(string $path, string|array $templates, bool $throwException = true): array|bool
    {
        if (is_string($templates)) {
            $templates = [$templates];
        }

        $resolvedTemplates = [];

        foreach ($templates as $template) {
            $template = str_replace("\0", "", $template); // remove any null bytes

            $template = preg_replace('/\s+/', ' ', $template); // remove whitespace

            $template = self::normalizePath($template); // normalize path

            if (!pathinfo($template, PATHINFO_EXTENSION)) { // allowed to render html files
                $template .= '.php';
            }

            $fullPath = $path . DS . $template;
            $template = realpath($path . DS . $template);

            if (!$template) {
                if ($throwException) {
                    throw new TemplateNotFound($fullPath);
                }
                return false;
            }

            if (!str_starts_with($template, $path)) {
                if ($throwException) {
                    throw new TemplateNotFound($template);
                }

                return false;
            }

            if (!is_readable($template)) {
                if ($throwException) {
                    throw new TemplateNotReadable($template);
                }

                return false;
            }

            $resolvedTemplates[] = $template;
        }

        return $resolvedTemplates;
    }

    /**
     * Normalize a file path by replacing slashes, backslashes, and dots with directory separator.
     *
     * @param string $path The path string to normalize.
     * @return string Normalized path string.
     */
    private static function normalizePath(string $path): string
    {
        return str_replace(['\\', '/', '.'], DS, $path);
    }
}
