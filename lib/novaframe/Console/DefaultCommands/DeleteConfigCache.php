<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;

class DeleteConfigCache extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'config:clear';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Deleted a generated cache file for all the config files';

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
     * - Example : $options = [
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
    protected string $usage = 'config:clear';

    /**
     * Command Action
     */
    public function action()
    {
        $file = BOOTSTRAP_PATH . 'cache/config.cache.php';

        if (file_exists($file)) {
            unlink($file);

            $this->box('Success', 'white', 'green', ['bold']);
            $this->success('Config cache deleted successfully');

            return self::SUCCESS;
        }

        $this->box('Success', 'white', 'green', ['bold']);
        $this->success('Config cache already deleted');

        return self::SUCCESS;
    }
}
