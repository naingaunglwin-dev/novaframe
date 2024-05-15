<?php

namespace Nova\Exception;

use Nova\Dotenv\Dotenv;
use Nova\Exception\Helper\ExceptionDisplay;
use Nova\HTTP\IncomingRequest;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Terminal;

class Handler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function set(): void
    {
        set_error_handler([$this, 'handle']);
        set_exception_handler([$this, 'handle']);
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

        $handler = 'fatalHandler';

        if (PHP_SAPI === 'cli') {
            $handler = 'cliHandler';
        }

        return $this->{$handler}($exception);
    }

    /**
     * Handle fatal errors.
     *
     * @param mixed $exception The fatal exception.
     * @return bool Whether the handling was successful.
     */
    private function fatalHandler(mixed $exception): bool
    {
        ob_start();

        $formatted = $this->formatter(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTrace()
        );

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

        echo $output;

        return false;
    }

    /**
     * Handle errors in CLI mode.
     *
     * @param mixed $exception The exception in CLI mode.
     * @return bool Whether the handling was successful.
     */
    private function cliHandler(mixed $exception): bool
    {
        $data = $this->formatter(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTrace()
        );

        $output = new ConsoleOutput();

        $output->writeln('');
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
                $content .= $traces['class'] . '->';
            }

            foreach ($traces['args'] as $key => $arg) {
                if (is_object($arg)) {
                    $traces['args'][$key] = $arg::class;
                }
            }

            $content .= $traces['function'] . '(' . implode(', ', $traces['args']) . ');';

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
