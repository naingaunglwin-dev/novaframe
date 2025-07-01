<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\RuntimeEnv;

class StorageUnlink extends Command
{

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'storage:unlink';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Unlink app/storage/public to app/public/storage';

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
    protected array $options = [];

    /**
     * Usage for command
     * - Example : $usage = 'command:name [argument] [option]'
     *
     * @var string
     */
    protected string $usage = 'storage:unlink';

    public function handle()
    {
        $public = Path::join(DIR_ROOT, 'public', 'storage');

        if (file_exists($public)) {
            if (RuntimeEnv::isWindows()) {
                exec("rmdir /S /Q " . escapeshellarg($public));
            } else {
                unlink($public);
            }

            $this->io->box('SUCCESS', 'default', 'green');
            $this->io->success('Symlink public/storage removed', ' ✓ ');
            return self::SUCCESS;
        } else {
            $this->io->box('INFO', 'default', 'cyan');
            $this->io->success('Already Unlinked', ' ✓ ');
            return self::SUCCESS;
        }
    }
}