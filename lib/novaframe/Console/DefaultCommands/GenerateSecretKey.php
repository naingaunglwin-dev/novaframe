<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;
use Nova\Helpers\Modules\File;

class GenerateSecretKey extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'key:generate';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Generate a secret key';

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
    protected string $usage = 'key:generate';

    /**
     * Command Action
     *
     * @return int
     */
    public function action(): int
    {
        return $this->generate();
    }

    /**
     * Generate a secret key
     *
     * @return int
     */
    private function generate(): int
    {
        $envFile   = ROOT_PATH . '/.env';

        if ($this->isSecretKeyAlreadyExists()) {
            $this->box('Success', 'white', 'green', ['bold']);
            $this->success('Your secret key is already exist.');
        } else {
            $key = "APP_KEY=".\csrf();

            $file = new File($envFile);

            $content = $file->getContent();

            $lines = array_filter(explode(PHP_EOL, $content), function($line) {
                return !str_starts_with(trim($line), 'APP_KEY=');
            });

            $appGroupLine = $this->getAppGroup($lines);

            if ($appGroupLine === null) {
                $lines[] = $key;
            } else {
                array_splice($lines, $appGroupLine + 1, 0, $key);
            }

            $file->writeContent(implode(PHP_EOL, $lines));

            $this->box('Success', 'white', 'green', ['bold']);
            $this->success("Your secret key is generated successfully");
        }

        return self::SUCCESS;
    }

    /**
     * Check if secret key exists
     *
     * @return bool
     */
    private function isSecretKeyAlreadyExists(): bool
    {
        return !empty(env('APP_KEY'));
    }

    /**
     * Get the APP group environment variables
     *
     * @param $lines
     * @return int|string|null
     */
    private function getAppGroup($lines): int|string|null
    {
        $appGroupEndLine = null;

        foreach ($lines as $index => $line) {
            if (str_starts_with($line, 'APP_')) {
                $appGroupEndLine = $index;
            }
        }

        return $appGroupEndLine;
    }
}
