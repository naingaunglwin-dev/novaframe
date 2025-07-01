<?php

namespace NovaFrame\Exception;

use NovaFrame\Helpers\Path\Path;

class Formatter
{
    private const SEVERITY_LEVEL = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'ERROR',
        E_NOTICE => 'INFO',
        E_CORE_ERROR => 'ERROR',
        E_CORE_WARNING => 'WARNING',
        E_COMPILE_ERROR => 'ERROR',
        E_COMPILE_WARNING => 'WARNING',
        E_USER_ERROR => 'ERROR',
        E_USER_WARNING => 'WARNING',
        E_USER_NOTICE => 'INFO',
        E_RECOVERABLE_ERROR => 'ERROR',
        E_DEPRECATED => 'WARNING',
        E_USER_DEPRECATED => 'INFO',
    ];

    public static function format(\Throwable $e, bool $isCli = false): array
    {
        $message = explode("\n", $e->getMessage())[0];

        $name = $e->getFile();
        $line = $e->getLine();

        $file = file($name);

        $contexts = static::readContext($file, $line);

        $backtraces = [];

        foreach ($contexts as $l => $context) {

            $backtraces[$name][$line][] = [
                'file' => $name,
                'display_line' => $l + 1,
                'context' => htmlspecialchars($context),
            ];
        }

        foreach ($e->getTrace() as $trace) {
            if (!isset($trace['file']) && !isset($trace['line'])) {
                continue;
            }

            if ($trace['file'] === $name && $trace['line'] === $line) {
                continue;
            }

            $traceContexts = static::readContext(file($trace['file']), $trace['line']);

            foreach ($traceContexts as $l => $traceContext) {
                $backtraces[$trace['file']][$trace['line']][] = [
                    'file' => $trace['file'],
                    'display_line' => $l + 1,
                    'context' => htmlspecialchars($traceContext),
                ];
            }
        }

        $type = get_class($e);

        $severity = 'ERROR';

        if (method_exists($e, 'getSeverity')) {
            $severity = self::SEVERITY_LEVEL[$e->getSeverity()] ?? self::SEVERITY_LEVEL[E_ERROR];
        }

        $headers = [];
        $included = [];

        if (!$isCli) {
            $headers = getallheaders();

            foreach (get_included_files() as $included_file) {
                $path = str_replace(DIR_ROOT, '', Path::normalize($included_file));

                $paths = explode(DS, $path);

                $basePath = $paths[0];

                unset($paths[0]);

                if (is_file($basePath)) {
                    $included['root'][] = [
                        'basepath' => DIR_ROOT,
                        'file' => $basePath
                    ];
                } else {
                    $included[$basePath][] = [
                        'basepath' => DIR_ROOT . $basePath,
                        'file' => implode(DS, $paths)
                    ];
                }
            }
        }

        return ['message' => $message, 'name' => $name, 'line' => $line, 'backtraces' => $backtraces, 'type' => $type, 'severity' => $severity, 'headers' => $headers, 'included' => $included];
    }

    public static function formatArgs($args): string
    {
        $result = '';

        if (count($args) > 5) {
            $result = "Array...";
        } else {
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    $result .= '[' . self::formatArgs($arg) . '], ';
                } else {
                    if (is_object($arg)) {
                        $arg = $arg::class;
                    }

                    $result .= "$arg, ";
                }
            }
        }

        // Remove the trailing comma and space if present
        return str_ends_with($result, ", ") ? substr($result, 0, -2) : $result;
    }

    private static function readContext($file, $line): array
    {
        $totalLineToRead = 5;

        $startLine = max(0, $line - $totalLineToRead);
        $endLine = min(count($file), $line + $totalLineToRead);

        return array_slice($file, $startLine, $endLine - $startLine, true);
    }
}
