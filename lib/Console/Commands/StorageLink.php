<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\RuntimeEnv;

class StorageLink extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'storage:link';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Link app/storage/public to app/public/storage';

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
    protected string $usage = 'storage:link';

    public function handle()
    {
        $public = Path::join(DIR_ROOT, 'public', 'storage');
        $storage = Path::join(DIR_STORAGE, 'public');

        if (file_exists($public) || is_link($public)) {
            $this->io->box('WARNING', 'default', 'yellow');
            $this->io->warning('This is already linked to app/storage/public to app/public/storage');
            return self::FAILURE;
        }

        if (is_link($public)) {
            $this->io->box('INFO', 'default', 'cyan');
            $this->io->success('Already Linked', ' ✓ ');
            return self::SUCCESS;
        }

        if (!is_dir($storage)) {
            mkdir($storage, 0777, true);
        }

        if (RuntimeEnv::isWindows() && !$this->isWindowsAdmin()) {
            // Windows: use mklink /J
            $cmd = sprintf('mklink /J "%s" "%s"', $public, $storage);
            exec($cmd, $output, $code);

            if ($code !== 0) {
                $this->io->error('Failed to create junction link on Windows.', ' 🞩 ');
                return self::FAILURE;
            }
        } else {
            // Window running as administrator
            // Linux/macOS: use symlink
            symlink($storage, $public);
        }

        $this->io->box('SUCCESS', 'default', 'green');
        $this->io->success('Storage symlink created', ' ✓ ');
        return self::SUCCESS;
    }
}
