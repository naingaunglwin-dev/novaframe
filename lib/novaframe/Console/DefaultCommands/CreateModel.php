<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;
use Nova\Console\DefaultCommands\Traits\ProcessResolver;
use Symfony\Component\Console\Input\InputArgument;

class CreateModel extends Command
{
    use ProcessResolver;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'make:model';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Create a new model class';

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
    protected array $arguments = [
        [
            'name'        => 'model',
            'description' => 'Name of the model to create class',
            'mode'        => InputArgument::REQUIRED
        ],
        [
            'name'        => 'table',
            'description' => 'Name of the table',
            'mode'        => InputArgument::OPTIONAL
        ]
    ];

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
    protected string $usage = 'make:model <model> <table>';

    /**
     * Command Action
     *
     * @return int
     */
    public function action(): int
    {
        return $this->resolve('model');
    }
}