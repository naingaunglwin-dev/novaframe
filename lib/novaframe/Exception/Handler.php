<?php

namespace Nova\Exception;

use Nova\Exception\Helper\ExceptionDisplay;
use Nova\Facade\Log;
use Nova\HTTP\IncomingRequest;
use Nova\HTTP\Response;
use Nova\Service\Dotenv\Dotenv;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Terminal;

class Handler implements HandlerInterface
{
    private const DEBUG_LEVELS = [
        E_ERROR             => 'ERROR',
        E_WARNING           => 'WARNING',
        E_PARSE             => 'ERROR',
        E_NOTICE            => 'INFO',
        E_CORE_ERROR        => 'ERROR',
        E_CORE_WARNING      => 'WARNING',
        E_COMPILE_ERROR     => 'ERROR',
        E_COMPILE_WARNING   => 'WARNING',
        E_USER_ERROR        => 'ERROR',
        E_USER_WARNING      => 'WARNING',
        E_USER_NOTICE       => 'INFO',
        E_STRICT            => 'INFO',
        E_RECOVERABLE_ERROR => 'ERROR',
        E_DEPRECATED        => 'WARNING',
        E_USER_DEPRECATED   => 'WARNING'
    ];

    /**
     * @inheritDoc
     */
    public function set(): void
    {
        set_error_handler($this->errorHandler(...), $this->definedErrorReportingLevel());
        set_exception_handler($this->handle(...));
        register_shutdown_function([$this, 'shutdownHandler']);
    }

    /**
     * @inheritDoc
     */
    public function handle(mixed $exception): bool
    {
        if (gettype($exception) !== 'object') {
            return true;
        }

        $severity = E_ERROR;

        if (method_exists($exception, 'getSeverity')) {
            $severity = $exception->getSeverity();
        }

        if (!$this->shouldHandle($severity)) {
            return true;
        }

        $handler = 'fatalHandler';

        if (PHP_SAPI === 'cli') {
            $handler = 'cliHandler';
        }

        return $this->{$handler}($exception);
    }

    /**
     * Error Handler
     *
     * @param $severity
     * @param $message
     * @param $file
     * @param $line
     * @return bool
     */
    private function errorHandler($severity, $message, $file, $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return true;
        }

        return $this->handle(new \ErrorException($message, 0, $severity, $file, $line));
    }

    /**
     * Determine if an error should be handled based on the debug level.
     *
     * @param int $severity The severity of the error.
     * @return bool True if the error should be handled, false otherwise.
     */
    private function shouldHandle(int $severity): bool
    {
        if ($this->definedErrorReportingLevel() === E_ALL) {
            return true;
        }

        return $severity >= $this->definedErrorReportingLevel();
    }

    /**
     * Return the user defined error_reporting level from configuration file
     *
     * @return int|mixed
     */
    private function definedErrorReportingLevel(): mixed
    {
        $config = config('app');

        return $config['error_reporting_level'][$config['environment']] ?? ($config['environment'] === 'development' ? E_ALL : 0);
    }

    /**
     * Handle fatal errors.
     *
     * @param mixed $exception The fatal exception.
     * @return bool Whether the handling was successful.
     */
    private function fatalHandler(mixed $exception): bool
    {
        Log::write(
            implode("\n", $this->logMessage($exception)),
            $this->determineLogLevel($exception),
            false
        );

        ob_start();

        $formatted = $this->formatter(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTrace()
        );

        $formatted['severity'] = self::DEBUG_LEVELS[E_ERROR];

        if (method_exists($exception, 'getSeverity')) {
            $formatted['severity'] = self::DEBUG_LEVELS[$exception->getSeverity()];
        }

        $request = IncomingRequest::createFromGlobals();

        $baseUrl = $request->getBaseUrl();

        $formatted['baseUrl'] = $baseUrl;

        $dotenv = new Dotenv();

        $dotenv->load();

        $environment = $dotenv->get('APP_ENVIRONMENT') ?? 'production';

        $config = include APP_PATH . 'Config/view.php';

        $path = rtrim($config['paths']['exception'], '/') . DIRECTORY_SEPARATOR . $environment . DIRECTORY_SEPARATOR;

        $novaframeExceptionHelper = new ExceptionDisplay($formatted);

        extract($formatted);

        require_once $path . 'exception.php';

        $output = ob_get_clean();

        ob_clean();

        $response = new Response($output, 500);

        $response->send();

        return false;
    }

    /**
     * Formatted log messages
     *
     * @param $exception
     * @return array
     */
    private function logMessage($exception): array
    {
        $message = $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();

        $count = 1;

        $trace = "\nStack Trace:";

        foreach ($exception->getTrace() as $traces) {
            if (empty($traces['file'])) {
                continue;
            }

            $trace .= "\n$count. {$traces['file']}({$traces['line']}): ";

            if (isset($traces['class'])) {
                $trace .= $traces['class'] . $traces['type'];
            }

            $trace .= $traces['function'];

            if (isset($traces['args'])) {

                foreach ($traces['args'] as $key => $arg) {
                    if (is_object($arg)) {
                        $traces['args'][$key] = $arg::class;
                    }
                }

                $trace .= "(";

                $trace .= $this->formatArgs($traces['args']);

                $trace .= ")";
            }

            $trace .= "\r";

            $count++;
        }

        return [$message, $trace];
    }

    /**
     * Determine the log level based on the exception type or error severity.
     *
     * @param mixed $exception The exception or error.
     * @return string The log level.
     */
    private function determineLogLevel(mixed $exception): string
    {
        // Default to ERROR level
        $level = 'ERROR';

        if ($exception instanceof \ErrorException) {
            $severity = $exception->getSeverity();
            if (isset(self::DEBUG_LEVELS[$severity])) {
                $level = self::DEBUG_LEVELS[$severity];
            }
        }

        return $level;
    }

    /**
     * Format the given trace argument array
     *
     * @param $args
     * @return string
     */
    private function formatArgs($args): string
    {
        $result = '';

        foreach ($args as $arg) {
            if (is_array($arg)) {
                $result .= '[' . $this->formatArgs($arg) . '], ';
            } else {
                $result .= "$arg, ";
            }
        }

        // Remove the trailing comma and space if present
        $result = str_ends_with($result, ", ") ? substr($result, 0, -2) : $result;

        return $result;
    }

    /**
     * Handle errors in CLI mode.
     *
     * @param mixed $exception The exception in CLI mode.
     * @return bool Whether the handling was successful.
     */
    private function cliHandler(mixed $exception): bool
    {
        Log::write(
            implode("\n", $this->logMessage($exception)),
            $this->determineLogLevel($exception),
            false
        );

        $data = $this->formatter(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTrace()
        );

        $output = new ConsoleOutput();

        $output->writeln("<error> Error </error> <fg=red>{$data['message']} in {$data['fileName']} on line {$data['line']}</>");
        $output->writeln('');

        $output->writeln("<comment>Stack trace</comment>");
        $output->writeln('');

        $count = 1;

        $terminal = new Terminal();

        $maxWidth = $terminal->getWidth();

        foreach ($exception->getTrace() as $traces) {
            if (empty($traces['file'])) {
                continue;
            }

            $output->writeln("<fg=white;options=bold>{$count}.</> <info>{$traces['file']} on line {$traces['line']}</info>");

            $firstCount = str_repeat(' ', strlen((string)$count)) . "   ";

            $content = $firstCount;

            if (isset($traces['class'])) {
                $content .= $traces['class'] . $traces['type'];
            }

            foreach ($traces['args'] as $key => $arg) {
                if (is_object($arg)) {
                    $traces['args'][$key] = $arg::class;
                }
            }

            $content .= $traces['function'] . "(\033[0;90m" . implode(', ', $traces['args']) . "\033[0m);";

            $indentation = str_repeat($firstCount, strlen((string)$count));

            $contentWrapped = wordwrap($content, $maxWidth - strlen($indentation), PHP_EOL . $indentation, true);

            $contentLines = explode(PHP_EOL, $contentWrapped);

            foreach ($contentLines as $line) {
                $output->writeln($line);
            }

            $output->writeln('');

            $count++;
        }

        return true;
    }

    /**
     * Shutdown handler function.
     *
     * This method is called when PHP script execution is about to end, whether
     * by normal termination or by calling exit().
     *
     * It checks for any errors that might have occurred during script execution
     * and handles them if found.
     *
     * @return void
     */
    private function shutdownHandler(): void
    {
        $errors = error_get_last();

        if ($errors === null) {
            return;
        }

        $this->handle(new \ErrorException($errors['message'], $errors['type'], 0, $errors['file'], $errors['line']));
    }

    /**
     * Format error and exception data for display.
     *
     * @param string $message The error or exception message.
     * @param string $file The file where the error occurred.
     * @param int $line The line number where the error occurred.
     * @param array $traces The stack trace of the error.
     * @return array Formatted error and exception data.
     */
    private function formatter(string $message, string $file, int $line, array $traces): array
    {
        $fileName = $file;
        $file     = file($file);

        list($startLine, $endLine) = $this->getMinAndMaxForLine(count($file), $line, 5);

        $context = array_slice($file, $startLine, $endLine - $startLine, true);

        $messages = [];

        foreach ($context as $lineNum => $lineContext) {
            $messages[$lineNum + 1] = htmlspecialchars($lineContext);
        }

        $traceMessages = [];

        foreach ($traces as $trace) {
            $traceMessages = $this->prepareTraceMessage($trace, $traceMessages);
        }

        $message = explode("\n", $message)[0];

        return compact('message', 'fileName', 'line', 'messages', 'traceMessages');
    }

    /**
     * Get the minimum and maximum lines for context around the error.
     *
     * @param int $total The total number of lines in the file.
     * @param int $lineNo The line number where the error occurred.
     * @param int $totalLine The total number of lines to include in the context.
     * @return array An array containing the minimum and maximum lines for context.
     */
    private function getMinAndMaxForLine(int $total, int $lineNo, int $totalLine): array
    {
        $startLine = max(0, (int)$lineNo - $totalLine);

        $endLine   = min($total, (int) $lineNo + $totalLine);

        return array($startLine, $endLine);
    }

    /**
     * Prepare trace messages for display.
     *
     * @param mixed $trace The trace data.
     * @param mixed $traceMessages The formatted trace messages.
     * @return array The updated trace messages.
     */
    private function prepareTraceMessage(mixed $trace, mixed $traceMessages): array
    {
        if (isset($trace['file']) && isset($trace['line'])) {
            $traceFile = $trace['file'];
            $traceLine = $trace['line'];
            $sourceFile = file($traceFile);
            list($startLine, $endLine) = $this->getMinAndMaxForLine(count($sourceFile), $traceLine, 5);

            $traceContext = array_slice($sourceFile, $startLine, $endLine - $startLine, true);

            foreach ($traceContext as $lineNum => $lineCont) {
                $traceMessages[$traceFile][$traceLine][$lineNum + 1] = htmlspecialchars($lineCont);
            }
        }

        return $traceMessages;
    }
}
