<?php

namespace NovaFrame\Console;

use NovaFrame\RuntimeEnv;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Process\Process;

class Command extends SymfonyCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = '';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = '';

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
     *                      'default'     => 'novaframe'
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
     *                      'short'       => 's',
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
     * @var ?InputInterface
     */
    protected ?InputInterface $input = null;

    /**
     * @var ?OutputInterface
     */
    protected ?OutputInterface $output = null;

    /**
     * @var ?ConsoleStyle
     */
    protected ?ConsoleStyle $io = null;

    public function __construct()
    {
        parent::__construct();

        $this->setup();
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->io     = new ConsoleStyle($input, $output);
    }

    private function setup(): void
    {
        $this->setName($this->name)
            ->setDescription($this->description);

        if (!empty($this->arguments)) {
            foreach ($this->arguments as $argument) {
                $this->validate($argument, 'argument');

                $this->addArgument($argument['name'], $argument['mode'], $argument['description'], $argument['default'] ?? null);
            }
        }

        if (!empty($this->options)) {
            foreach ($this->options as $option) {
                $this->validate($option, 'option');

                $this->addOption($option['name'], $option['short'], $option['mode'], $option['description'], $option['default'] ?? null);
            }
        }

        if (!empty($this->usage)) {
            $this->addUsage($this->usage);
        }
    }

    private function validate(array $resource, string $type): void
    {
        $arguments = ['name', 'description', 'mode'];
        $options   = ['name', 'short', 'description', 'mode'];

        $argumentModes = [InputArgument::OPTIONAL, InputArgument::REQUIRED, InputArgument::IS_ARRAY];
        $optionModes   = [InputOption::VALUE_NONE, InputOption::VALUE_OPTIONAL, InputOption::VALUE_NEGATABLE, InputOption::VALUE_REQUIRED, InputOption::VALUE_IS_ARRAY];

        if ($type === 'argument') {
            foreach ($arguments as $argument) {
                if (!array_key_exists($argument, $resource)) {
                    $this->output->writeln('<error>Argument must contain ' . $argument . '</error>');
                    exit(1);
                }
            }
        } elseif ($type === 'option') {
            foreach ($options as $option) {
                if (!array_key_exists($option, $resource)) {
                    $this->output->writeln('<error>Option must contain ' . $option . '</error>');
                    exit(1);
                }
            }
        }

        $array = $type === 'argument' ? $arguments : $options;
        $modes = $type === 'argument' ? $argumentModes : $optionModes;

        foreach ($array as $key) {
            if (!array_key_exists($key, $resource)) {
                $this->output->writeln(
                    '<error>' . ($type === 'argument' ? 'Argument' : 'Option') . ' must contain ' . $key . '</error>'
                );

                exit(self::FAILURE);
            }
        }

        if (!in_array($resource['mode'], $modes)) {
            $this->output->writeln(
                'Invalid ' . ($type === 'argument' ? 'Argument' : 'Option') . ' mode: ' . $resource['mode']
            );

            exit(1);
        }
    }

    public function handle()
    {
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->newLine();

        if (env('APP_ENV', 'production') === 'production') {
            $output->setVerbosity($output::VERBOSITY_NORMAL);
        } else {
            $output->setVerbosity($output::VERBOSITY_DEBUG);
        }

        $result = $this->handle();

        return $result ?? self::SUCCESS;
    }

    protected function runCommand(string $command, array $args = [], bool $return = false)
    {
        $process = new Process(array_merge(['php', 'nova', ...explode(' ', $command)], $args), DIR_ROOT);
        $process->run();

        if ($return) {
            return $process;
        }

        if (!$process->isSuccessful()) {
            $this->io->error("Failed to run command: $command");
            $this->io->writeln($process->getErrorOutput());
        } else {
            $this->io->note("Executed: $command");
        }
    }

    protected function isRunningAsAdmin(): bool
    {
        if (RuntimeEnv::isWindows()) {
            return $this->isWindowsAdmin();
        }

        return $this->isRootUser();
    }

    protected function isRootUser(): bool
    {
        return function_exists('posix_geteuid') && posix_geteuid() === 0;
    }

    protected function isWindowsAdmin(): bool {
        if (stripos(PHP_OS_FAMILY, 'Windows') === false) {
            return false;
        }

        $output = [];
        exec('whoami /groups', $output);

        foreach ($output as $line) {
            if (stripos($line, 'S-1-16-12288') !== false || stripos($line, 'Administrators') !== false) {
                return true;
            }
        }

        return false;
    }
}
