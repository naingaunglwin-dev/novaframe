<?php

namespace Nova\Console;

use Nova\Console\Traits\Messages;
use Nova\Console\Traits\Methods;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    use Methods;
    use Messages;

    protected const OUTPUT_NORMAL = 1;

    protected const OUTPUT_PLAIN = 4;

    protected const OUTPUT_RAW = 2;

    protected const VERBOSITY_DEBUG =  256;

    protected const VERBOSITY_NORMAL = 32;

    protected const VERBOSITY_QUIET = 16;

    protected const VERBOSITY_VERBOSE = 64;

    protected const VERBOSITY_VERY_VERBOSE = 128;

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
     * @var InputInterface|ArgvInput
     */
    protected InputInterface|ArgvInput $input;

    /**
     * @var OutputInterface|ConsoleOutput
     */
    protected OutputInterface|ConsoleOutput $output;

    public function __construct()
    {
        parent::__construct();

        $this->input  = new ArgvInput();
        $this->output = new ConsoleOutput();

        $this->defineConfiguration();
    }

    /**
     * @inheritDoc
     */
    public function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input  = $input;
        $this->output = $output;
    }

    /**
     * Configures the console command.
     *
     * Sets the name, description, arguments, and options for the command.
     */
    private function defineConfiguration(): void
    {
        $this->setName($this->name)
            ->setDescription($this->description);

        if (!empty($this->arguments)) {

            if (!is_array($this->arguments)) {
                exit("<error>Argument must be Array. " . gettype($this->arguments) . " given</error>");
            }

            foreach ($this->arguments as $index => $argument) {
                if (!empty($argument)) {
                    $this->check($argument, 'argument');
                    $this->addArgument($argument['name'], $argument['mode'], $argument['description']);
                }
            }
        }

        if (!empty($this->options)) {

            if (!is_array($this->options)) {
                exit("<error>Option must be Array. " . gettype($this->options) . " given</error>");
            }

            foreach ($this->options as $index => $option) {
                if (!empty($option)) {
                    $this->check($option, 'option');
                    $this->addOption($option['name'], $option['shortcut'], $option['mode'], $option['description'], $option['mode'] !== InputOption::VALUE_NONE ? $option['default'] ?? false : null);
                }
            }
        }

        if (!empty($this->usage)) {
            $this->addUsage($this->usage);
        }
    }

    /**
     * Check argument and option array contain a must need array keys
     *
     * @param array $array Argument or Option array
     * @param string $type `argument` or `option` type to perform checking
     */
    private function check(array $array, string $type): void
    {
        $arguments = ['name', 'description', 'mode'];

        $options   = ['name', 'shortcut', 'mode', 'description'];

        if ($type === 'argument') {
            foreach ($arguments as $argument) {
                if (empty($array[$argument])) {
                    exit('Argument array must have key : ' . $argument);
                }
            }

            if (!in_array($array['mode'], [InputArgument::OPTIONAL, InputArgument::REQUIRED, InputArgument::IS_ARRAY])) {
                exit("Invalid Argument Mode : " . $array['mode']);
            }
        } elseif ($type === 'option') {
            foreach ($options as $option) {
                if (empty($array[$option])) {
                    exit('Option array must have key : ' . $option);
                }

                if (!in_array($array['mode'], [
                    InputOption::VALUE_REQUIRED, InputOption::VALUE_OPTIONAL, InputOption::VALUE_IS_ARRAY, InputOption::VALUE_NEGATABLE, InputOption::VALUE_NONE
                ])) {
                    exit('Invalid Option Mode : ' . $array['mode']);
                }
            }
        }
    }

    /**
     * Command Action
     */
    public function action()
    {
        return self::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output->writeln('');

        if (env('APP_ENVIRONMENT', 'production') === 'production') {
            $this->setVerbosity(self::VERBOSITY_QUIET);
        } else {
            $this->setVerbosity(self::VERBOSITY_DEBUG);
        }

        $this->action();

        return self::SUCCESS;
    }
}
