<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CacheClear extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'cache:clear';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Clear the cache files';

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
    protected string $usage = 'cache:clear';

    /**
     * Command Action
     *
     * @return int
     */
    public function action(): int
    {
        $dir = TMP_PATH . 'cache';

        if (!is_dir($dir)) {
            return $this->alreadyCleared();
        }

        $caches = scandir($dir);

        if (!$caches || count($caches) == 0 || count(array_diff($caches, ['.', '..'])) == 0) {
            return $this->alreadyCleared();
        }

        $caches = array_diff($caches, ['.', '..']);

        foreach ($caches as $cache) {
            if (is_file("$dir/$cache")) {
                unlink("$dir/$cache");
            }
        }

        $this->box('Success', 'white', 'green', ['bold']);
        $this->success('Cache cleared');

        return self::SUCCESS;
    }

    private function alreadyCleared(): int
    {
        $this->box('Success', 'white', 'green', ['bold']);
        $this->success('Cache already cleared');

        return self::SUCCESS;
    }
}
