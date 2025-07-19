<?php

namespace NovaFrame\Exception;

use NovaFrame\Facade\Config;
use NovaFrame\Facade\Env;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\Http\Exceptions\HttpException;
use NovaFrame\Http\Exceptions\ValidationException;
use NovaFrame\Http\Response;
use NovaFrame\RuntimeEnv;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Terminal;

class ExceptionHandler
{
    private array $severityPriority = [
        E_ERROR     => 1,
        E_WARNING   => 2,
        E_PARSE     => 3,
        E_NOTICE    => 4,
        E_DEPRECATED => 6,
    ];

    private array $handlers = [];

    public function __construct(
        private readonly ?string $fallback = null
    )
    {
    }

    public function initialize(): void
    {
        set_error_handler($this->errorHandler(...), $this->definedErrorReportingLevel());
        set_exception_handler($this->handle(...));
        register_shutdown_function([$this, 'shutdownHandler']);
    }

    public function register(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function handle(\Throwable $e)
    {
        if ($e instanceof ValidationException) {
            app()->terminate($e->redirect(), new Response());
            return true;
        }

        $severity = E_ERROR;

        if (method_exists($e, 'getSeverity')) {
            $severity = $e->getSeverity();
        }

        if (!$this->shouldHandle($severity)) {
            return $this->handleWithFallback($e);
        }

        foreach ($this->handlers as $handler) {
            $handler->handle($e);
        }

        return $this->{RuntimeEnv::envIs('cli') ? 'cliHandler' : 'fatalHandler'}($e);
    }

    private function handleWithFallback(\Throwable $e): true
    {
        if (!$this->fallback) {
            return true;
        }

        ob_start();
        include $this->fallback;
        $output = ob_get_clean();

        $response = new Response();
        $response->setContent($output)
            ->setStatusCode(500)
            ->send();

        $response->clean();

        return true;
    }

    private function errorHandler($severity, $message, $file, $line)
    {
        if (!(error_reporting() && $severity)) {
            return true;
        }

        return $this->handle(new \ErrorException($message, 0, $severity, $file, $line));
    }

    private function fatalHandler(\Throwable $e)
    {
        try {
            $env = Env::get('APP_ENV', 'production');
            date_default_timezone_set(Config::get('app.timezone', 'UTC'));
        } catch (\Throwable $e) {
            $env = 'production';
        }

        $status = 500;

        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
        }

        /** @var Response $response */
        $response = new Response();

        if ($env === 'production') {
            $response->setContent(view('errors.production.500'))
                ->setStatusCode($status)
                ->send();
            $response->clean();
            return true;
        }

        while (ob_get_level() > 0) {
            @ob_end_clean();
        }

        ob_start();

        $formatted = Formatter::format($e);

        extract($formatted, EXTR_SKIP);

        require Path::join(DIR_APP, 'Views', 'errors', 'development', '500.php');

        $content = ob_get_clean();

        $response->setContent($content)
            ->setStatusCode($status)
            ->send();
        $response->clean();

        exit;
    }

    private function cliHandler(\Throwable $e)
    {
        $formatted = Formatter::format($e, true);

        $output = new ConsoleOutput();

        $error = "<error> {$formatted['severity']} </error> <fg=red>{$formatted['message']}";

        if (isset($formatted['name'])) {
            $error .= " in {$formatted['name']} at line {$formatted['line']}";
        }

        $error .="</>";

        $output->writeln('');
        $output->writeln($error);
        $output->writeln('');

        $output->writeln("<comment>Stack trace:</comment>");
        $output->writeln('');

        $count = 1;

        $terminal = new Terminal();

        $terminalMaxWidth = $terminal->getWidth();

        foreach ($e->getTrace() as $trace) {
            if (empty($trace['file'])) {
                continue;
            }

            $output->writeln("<fg=white;options=bold>{$count}.</> <info>{$trace['file']} at line {$trace['line']}</info>");

            $firstCount = str_repeat(' ', strlen((string)$count)) . "   ";

            $content = $firstCount;

            if (isset($trace['class'])) {
                $content .= $trace['class'] . $trace['type'];
            }

            if (isset($trace['args'])) {
                foreach ($trace['args'] as $key => $arg) {
                    if (is_object($arg)) {
                        $trace['args'][$key] = $arg::class;
                    }
                }
            }

            $content .= $trace['function'] . "(\033[0;90m";

            if (isset($trace['args'])) {
                $content .= Formatter::formatArgs($trace['args']);
            }

            $content .= "\033[0m)";

            $indentation = str_repeat($firstCount, strlen((string)$count));

            $contentWrapped = wordwrap($content, $terminalMaxWidth - strlen($indentation), PHP_EOL . $indentation, true);

            $contentLines = explode(PHP_EOL, $contentWrapped);

            foreach ($contentLines as $line) {
                $output->writeln("{$line}");
            }

            $output->writeln('');

            $count++;
        }

        exit (1);
    }

    private function definedErrorReportingLevel()
    {
        $app = Config::get('app');

        return $app['error_reporting'][$app['env']] ?? ($app['env'] === 'development' ? E_ALL : 0);
    }

    private function shouldHandle(int $severity): bool
    {
        $definedErrorReportingLevel = $this->definedErrorReportingLevel();

        if ($definedErrorReportingLevel === E_ALL) {
            return true;
        }

        $definedErrorPriority = $this->severityPriority[$definedErrorReportingLevel] ?? 999;
        $actualErrorPriority = $this->severityPriority[$severity] ?? 0;

        return $actualErrorPriority >= $definedErrorPriority;
    }

    private function shutdownHandler(): void
    {
        $errors = error_get_last();

        if (empty($errors)) {
            return;
        }

        $this->handle(new \ErrorException($errors['message'], 0, $errors['type'], $errors['file'], $errors['line']));
    }
}
