<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Process\Process;

class Serve extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'serve';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Run local server';

    /**
     * An array of command arguments.
     * Each argument is defined by an associative array with keys:
     * - 'name': The name of the argument.
     * - 'description': The description of the argument.
     * - 'mode': Argument mode
     * - Example : $arguments = [
     *                  [
     *                      'name'        => 'argName',
     *                      'description' => 'argDescription',
     *                      'mode'        => InputArgument::REQUIRED
     *                  ],
     *              ];
     *
     * @var array
     */
    protected array $arguments = [];

    /**
     * An array of command options.
     * Each option is defined by an associative array with keys:
     * - 'name': The name of the option.
     * - 'shortcut': The shortcut for the option.
     * - 'mode': Option mode
     * - 'description': The description of the option.
     * - 'default': default value
     * - Example : $arguments = [
     *                  [
     *                      'name'        => 'optionName',
     *                      'shortcut'    => 's',
     *                      'mode'        => InputOption::VALUE_REQUIRED,
     *                      'description' => 'optionDescription',
     *                      'default'     => false
     *                  ],
     *              ];
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Usage for command
     * - Example : $usage = 'command:name [argument] [option]'
     *
     * @var string
     */
    protected string $usage = '';

    /**
     * Command Action
     *
     * @return void
     */
    public function action(): void
    {
        $process = new Process(["php", "-S", "localhost:8080", "-t", PUBLIC_PATH]);

        $process->setTimeout(null);

        $process->start();

        usleep(700000);

        if ($process->isRunning()) {
            $this->box('INFO', 'white', 'cyan', ['bold']);
            $this->comment('Server is running on <href="http://localhost:8080">http://localhost:8080</>', true);
            $this->writeln("");
        } else {
            $this->error("Error starting server", [], true);
            $this->writeln("<fg=gray>{$process->getErrorOutput()}</>");
        }

        while ($process->isRunning()) {
            $success = $process->getIncrementalOutput();
            $error   = $process->getIncrementalErrorOutput();

            $output = $success . $error;

            $this->handle($output);

            usleep(100000);
        }

        $process->wait();
    }

    private function handle($output): void
    {
        if (!empty($output)) {
            $lines = explode("\n", trim($output));

            foreach ($lines as $line) {

                if (str_contains($line, "Development")) {
                    continue;
                }

                $line = preg_replace('/\[.*?\]/', '', $line,1);

                $start = ">";

                $runtime = date("Y-m-d H:i:s");

                $this->write(sprintf("<fg=gray> %s %s</> ", $start, $runtime));

                $lineLength = mb_strlen($start . " " . $runtime);

                if (str_contains(preg_replace('/\[.*?\]/', '', $line,1), '[') && !str_contains(preg_replace('/\[.*?\]/', '', $line,1), "Accepted")) {

                    $data = explode(" ", preg_replace('/\[.*?\]/', '', $line,1));

                    foreach ($data as $str) {
                        if (str_contains($str, "[")) {
                            preg_match('/\[.*?\]/', $str, $matches);

                            $status = isset($matches[0]) ? substr(substr($matches[0], 0, -1), 1) : 500;

                            if (in_array($status, [200, 201, 202, 203, 204, 205, 206, 207, 208, 226])) {
                                $this->success("$status ", ['bold']);
                            } elseif (in_array($status, array_keys($this->errorStatus()))) {
                                $this->error("$status ", ['bold']);
                            } else {
                                $this->write("<fg=yellow;options=bold>$status </>");
                            }

                            $lineLength += mb_strlen(" $status ");
                        }
                    }

                    if (preg_match('/\b(GET|POST|HEAD|PUT|PATCH|DELETE)\b/', $line, $matches)) {
                        $this->box($matches[0], 'white', 'cyan', ['bold']);
                        $lineLength += mb_strlen("  " . $matches[0] . "  ");
                    }

                    if (str_contains($line, "/")) {
                        $cut = substr($line, strpos($line, "/"));

                        $cut = '"'. $cut . '" ';

                        $this->write("<options=bold>" . $cut . "</>");

                        $lineLength += mb_strlen($cut);
                    }

                } else {

                    preg_match_all('/\[\S+\]:\d+ (\w+)/', $line, $matches);

                    $text = '';

                    foreach ($matches[1] as $match) {
                        $text .= "$match ";
                    }

                    $this->write("<fg=white>$text</>");
                    $lineLength += mb_strlen(" " . $text . " ");
                }

                $remainingSpace = (new Terminal())->getWidth() - ($lineLength + 1);
                $dots = str_repeat('.', $remainingSpace);

                $this->writeln("<fg=gray>$dots</>");
            }
        }
    }

    private function errorStatus(): array
    {
        return [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Payload Too Large',
            414 => 'URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            421 => 'Misdirected Request',
            422 => 'Unprocessable Content',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Too Early',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',
            499 => 'Client Closed Request',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
            599 => 'Network Connect Timeout Error'
        ];
    }
}
