<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Console\Commands\Traits\CreationProcessResolver;
use Symfony\Component\Console\Input\InputArgument;

class CreateMiddleware extends Command
{
    use CreationProcessResolver;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'make:middleware';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Create a new middleware class';

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
            'name'        => 'middleware',
            'description' => 'Name of the middleware to create',
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
    protected string $usage = 'make:middleware <middleware>';

    public function handle()
    {
        return $this->resolve('middleware');
    }
}
