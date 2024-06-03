<?php

namespace Nova\Exception\Helper;

class ExceptionDisplay
{
    /**
     * Array containing the exception messages.
     *
     * @var array
     */
    private static array $messages;

    public function __construct(array $messages)
    {
        self::$messages = $messages;
    }

    /**
     * Get the exception message.
     *
     * @param bool $full Whether to return the full message or just the error type.
     * @return string|null The exception message.
     */
    public static function getMessage(bool $full = true): ?string
    {
        if (!$full) {
            $messages = self::$messages['message'];

            $messages = explode(':', $messages);

            return self::return($messages[0]);
        }

        return self::return(
            sprintf("<span class='severity'>%s</span>%s", self::$messages['severity'], self::$messages['message'])
        );
    }

    /**
     * Get the file where the exception occurred.
     *
     * @return string|null The file path.
     */
    public static function getFile(): ?string
    {
        return self::return(self::$messages['fileName']);
    }

    /**
     * Get the line number where the exception occurred.
     *
     * @return int|null The line number.
     */
    public static function getLine(): ?int
    {
        return self::return(self::$messages['line']);
    }

    /**
     * Display the trace messages.
     *
     * @return void
     */
    public static function displayTraceMessages(): void
    {
        foreach (self::$messages['traceMessages'] as $file => $data) {
            foreach ($data as $line => $content) {
                echo "<div class='trace-messages-box'>";
                echo "<p class='error-title'>" . $file . " on line " . $line . "</p>";
                echo "<div class='error-code'>";

                $icon = ExceptionDisplay::getIcon($file);

                echo "<div class='error-icon'> <i class='$icon'></i> </div>";

                foreach ($content as $key => $msg) {
                    $txt = "<span class='code-line ";
                    $txt .= $key == $line ? 'error-line' : '';

                    $trimed = str_replace(' ', '', $msg);

                    $msg = str_replace(' ', '<span style="color:#a1a1a1;opacity:0.4"> · </span>', $msg);

                    if (str_starts_with($trimed, "//")) {
                        $msg = '<span class="comment">' . $msg .'</span>';
                    }

                    $msg = ExceptionDisplay::highlightPhpKeywords($msg);

                    $msg = ExceptionDisplay::setColorToVariable($msg);

                    $msg = ExceptionDisplay::highlightFunctionsAndMethods($msg);

                    $txt .= "'><p>$key&nbsp; &nbsp;$msg</p></span>";

                    echo $txt;
                }

                echo "</div>";
                echo "</div>";
            }
        }
    }

    /**
     * Display the current error message.
     *
     * @return void
     */
    public static function displayCurrentErrorMessage(): void
    {
        $line = ExceptionDisplay::getLine();

        echo "<p class='error-title'>" . ExceptionDisplay::getFile() . " on line " . $line . "</p>";

        echo "<div class='error-code'>";

        $icon = ExceptionDisplay::getIcon();

        echo "<div class='error-icon'> <i class='$icon'></i> </div>";

        foreach (self::$messages['messages'] as $key => $message) {
            $txt = "<span class='code-line ";
            $txt .= $key == $line ? 'error-line' : '';

            $trimed = str_replace(' ', '', $message);

            $message = str_replace(' ', '<span style="color:#a1a1a1;opacity:0.4"> · </span>', $message);

            if (str_starts_with($trimed, "//")) {
                $message = '<span class="comment">' . $message .'</span>';
            }

            $message = ExceptionDisplay::highlightPhpKeywords($message);

            $message = ExceptionDisplay::highlightFunctionsAndMethods($message);

            $message = ExceptionDisplay::setColorToVariable($message);

            $txt .= "'><p class='line'>$key&nbsp; &nbsp;$message</p></span>";

            echo $txt;
        }

        echo "</div>";
    }

    /**
     * Helper method to return data with optional null coalescing.
     *
     * @param mixed $data The data to return.
     * @return mixed The data or null if not set.
     */
    private static function return(mixed $data = null): mixed
    {
        return $data ?? null;
    }

    /**
     * Get the file extension from a file path.
     *
     * @param string $file The file path.
     * @return string The file extension in lowercase.
     */
    private static function getFileExtension(string $file): string
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    /**
     * Get the icon class for a given file extension.
     *
     * @param string|null $file The file path (optional).
     * @return string The icon class for the file extension.
     */
    private static function getIcon(string $file = null): string
    {
        $extension = ExceptionDisplay::getFileExtension($file ?? ExceptionDisplay::getFile());

        $icon = 'fa-brands ';

        if ($extension == 'html') {
            $icon .= 'fa-html5';
        } elseif ($extension == 'js') {
            $icon .= 'square-js';
        } else {
            $icon .= 'fa-php';
        }

        return $icon;
    }

    /**
     * Set a color to variables within a code snippet.
     *
     * @param string $code The code snippet.
     * @return string The code snippet with variables wrapped in <span class="variable"> tags.
     */
    private static function setColorToVariable(string $code): string
    {
        if (str_contains($code, '<span class="comment">')) {
            return  $code;
        }

        $pattern = '/(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/';

        // Use preg_replace_callback to replace all matches with the span tag
        return preg_replace_callback($pattern, function($matches) {
            // Wrap the matched variable in a span tag with the specified style
            return '<span class="variable">' . $matches[0] . '</span>';
        }, $code);
    }

    /**
     * Highlight PHP keywords within a code snippet.
     *
     * @param string $code The code snippet.
     * @return string The code snippet with PHP keywords wrapped in <span class="keyword"> tags.
     */
    private static function highlightPhpKeywords(string $code): string
    {
        if (str_contains($code, '<span class="comment">')) {
            return  $code;
        }

        $php_keywords = array('abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'finally', 'for', 'foreach', 'function', 'fn', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield');

        // Constructing the regex pattern
        $pattern = '/\b(' . implode('|', $php_keywords) . ')\b/';

        // Replace PHP keywords with <span class="keyword">keyword</span>
        return preg_replace($pattern, '<span class="keyword">$1</span>', $code);
    }

    /**
     * Highlight functions and methods within a code snippet.
     *
     * @param string $code The code snippet.
     * @return string The code snippet with functions and methods wrapped in <span class="function"> tags.
     */
    private static function highlightFunctionsAndMethods(string $code): string
    {
        if (str_contains($code, '<span class="comment">')) {
            return  $code;
        }

        $pattern = '/\b(?:function\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\()|' .
            '(?:(?<=->|\b)\s*([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*(?=\())|' .
            '(?:(?<=::|\b)\s*([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?=\s*\())/';

        preg_match_all($pattern, $code, $matches, PREG_OFFSET_CAPTURE);

        $offset = 0;

        foreach ($matches[0] as $match) {
            if (empty($match[0])) {
                continue;
            }
            $position = $match[1] + $offset;

            $code = substr_replace($code, '<span class="function">' . $match[0] . '</span>', $position, strlen($match[0]));

            $offset += strlen('<span class="function"' . $match[0] . '</span>') - strlen($match[0]) + 1;
        }

        return $code;
    }

    /**
     * Get the base URL for the application, optionally appending a URL path.
     *
     * @param string $url The URL path to append (optional).
     * @return string The base URL with the appended path.
     */
    public static function getBaseUrl(string $url = ''): string
    {
        $baseUrl = self::$messages['baseUrl'];

        if (str_ends_with($baseUrl, '/')) {
            $baseUrl = substr($baseUrl, 0, -1);
        }

        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return $baseUrl . $url;
    }
}