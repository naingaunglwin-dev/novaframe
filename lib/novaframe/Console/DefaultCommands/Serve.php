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

        sleep(1);

        if ($process->isRunning()) {
            $this->box('Success', 'white', 'green', ['bold']);
            $this->comment('Server is running on <href="http://localhost:8080">http://localhost:8080</>', true);
        } else {
            $this->error("Error starting server", [], true);
            $this->writeln("<fg=gray>{$process->getErrorOutput()}</>");
        }

        while ($process->isRunning()) {
            $success = $process->getIncrementalOutput();
            $error   = $process->getIncrementalErrorOutput();

            $output = $success . $error;

            if (!empty($output)) {
                $lines = explode("\n", trim($output));

                foreach ($lines as $line) {
                    $data = explode(" ", $line);

                    if (isset($data[7]) && strtolower($data[7]) === 'development') {
                        continue;
                    }

                    $this->write("<fg=gray>" . $data[3] . " </>");

                    $lineLength = 1;

                    if (str_contains($data[6], '[')) {
                        (int)$status = preg_replace('/[^\d]/', '', $data[6]);

                        if (in_array($status, [200, 201, 202, 203, 204, 205, 206, 207, 208, 226])) {
                            $this->success("[$status]", ['bold']);
                        } elseif (in_array($status, array_keys($this->errorStatus()))) {
                            $this->error("[$status]", ['bold']);
                        } else {
                            $this->write("<fg=yellow;options=bold>[$status]</>");
                        }

                        $lineLength += mb_strlen($data[3] . "  " . $data[6]);

                    } else {
                        $this->write("<fg=magenta>" . $data[6] . " </>");

                        $lineLength += mb_strlen($data[3] . " " . $data[6] . " ");
                    }

                    if (isset($data[7])) {
                        $this->write(' ');
                        if (in_array($data[7], ['GET', 'POST', 'HEAD', 'PUT', 'PATCH', 'DELETE'])) {
                            $this->box($data[7], 'white', 'cyan', ['bold']);
                            $lineLength += mb_strlen("  " . $data[7]);
                        } else {
                            $this->write(" " . $data[7] . " ");
                            $lineLength += mb_strlen("  " . $data[7] . "  ");
                        }
                    }

                    if (isset($data[8])) {
                        if (str_starts_with($data[8], '/')) {
                            $this->write("<options=bold>" . $data[8] . "</>");
                        } else {
                            $this->write($data[8]);
                        }

                        $lineLength += mb_strlen($data[8]);
                    }

                    $remainingSpace = (new Terminal())->getWidth() - $lineLength;
                    $dots = str_repeat('.', $remainingSpace);

                    $this->writeln("<fg=gray>$dots</>");
                }
            }

            usleep(100000);
        }

        $process->wait();
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
