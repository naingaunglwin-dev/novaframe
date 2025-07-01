<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class Seed extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'db:seed';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Run seeding';

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
     *                      'short'       => 's',
     *                      'mode'        => InputOption::VALUE_REQUIRED,
     *                      'description' => 'optionDescription',
     *                      'default'     => false
     *                  ],
     *              ];
     *
     * @var array
     */
    protected array $options = [
        [
            'name' => 'seeder',
            'short' => 's',
            'mode' => InputOption::VALUE_OPTIONAL,
            'description' => 'Specific seeder to run',
        ]
    ];

    /**
     * Usage for command
     * - Example : $usage = 'command:name [argument] [option]'
     *
     * @var string
     */
    protected string $usage = 'db:seed';

    public function handle()
    {
        $seeder = new \NovaFrame\Database\Seed($this->io);

        $seeder->run($this->input->getOption('seeder'));

        return self::SUCCESS;
    }
}
