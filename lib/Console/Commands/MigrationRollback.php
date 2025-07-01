<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Database\Migrate;
use Symfony\Component\Console\Input\InputOption;

class MigrationRollback extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'migrate:rollback';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Rollback migration';

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
            'name' => 'batch',
            'short' => 'b',
            'mode' => InputOption::VALUE_OPTIONAL,
            'description' => 'Rollback all migrations down to the specified version.',
        ],
        [
            'name' => 'all',
            'short' => 'a',
            'mode' => InputOption::VALUE_NONE,
            'description' => 'Rollback all migrations.',
        ]
    ];

    /**
     * Usage for command
     * - Example : $usage = 'command:name [argument] [option]'
     *
     * @var string
     */
    protected string $usage = 'migrate:rollback [--batch=<version>] [--all|-a]';

    /**
     * handle command
     */
    public function handle()
    {
        $migrate = new Migrate($this->io);

        if ($this->input->getOption('all')) {
            $migrate->run(Migrate::MODE_DOWN, dropAll: true);
            return self::SUCCESS;
        }

        $version = null;

        if ($this->input->getOption('batch')) {
            $version = (int)$this->input->getOption('batch');
        }

        $migrate->run(Migrate::MODE_DOWN, $version);

        return self::SUCCESS;
    }
}
