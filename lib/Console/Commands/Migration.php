<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Database\Migrate;
use Symfony\Component\Console\Input\InputOption;

class Migration extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'migrate';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Run migration';

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
            'name' => 'refresh',
            'short' => 'r',
            'mode' => InputOption::VALUE_NONE,
            'description' => 'Refresh all migrations (rollback and re-run)',
        ]
    ];

    /**
     * Usage for command
     * - Example : $usage = 'command:name [argument] [option]'
     *
     * @var string
     */
    protected string $usage = 'migrate [--refresh|-r]';

    public function handle()
    {
        $migrate = new Migrate($this->io);

        $mode = Migrate::MODE_UP;

        if ($this->input->getOption('refresh')) {
            $mode = Migrate::MODE_REFRESH;
        }

        $migrate->run($mode);

        return self::SUCCESS;
    }
}
