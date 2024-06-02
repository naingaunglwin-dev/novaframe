<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;
use Nova\Facade\Log;

class LogClear extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'log:clear';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Clear the log files';

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
    protected string $usage = 'log:clear';

    /**
     * Command Action
     *
     * @return int
     */
    public function action(): int
    {
        Log::clearLogs();

        $this->box('Success', 'white', 'green', ['bold']);
        $this->success('Log cleared');

        return self::SUCCESS;
    }
}
