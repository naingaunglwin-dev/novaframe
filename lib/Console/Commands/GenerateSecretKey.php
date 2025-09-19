<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Env\Env;
use NovaFrame\Helpers\FileSystem\FileSystem;
use NovaFrame\Helpers\Path\Path;

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
    protected string $description = 'Generate a secret key for application';

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
    protected string $usage = 'key:generate';

    public function handle()
    {
        $envs = array_filter(glob(DIR_ROOT . ''), );

        $existedKey = '';
        $existedFile = '';

        $key = bin2hex(random_bytes(32));

        foreach ($envs as $env) {
            if (!file_exists(Path::join(DIR_ROOT, $env))) {
                continue;
            }

            if ($this->isSecretKeyAlreadyExists($env)) {
                $this->io->box('Success', 'white', 'green', ['bold'], true);
                $fullpath = Path::join(DIR_ROOT, $env);
                $this->io->success(" [$fullpath] 🠆 secret key already exists", true, ' ✓');

                $existedKey = $this->getSecretKey($env);
                $existedFile = Path::join(DIR_ROOT, $env);
                continue;
            }

            if (!empty($existedFile)) {
                $choice = $this->io->choice('<bg=cyan> ' . Path::join(DIR_ROOT, $env) . ' </>' . PHP_EOL . ' We already have a secret key in [' . $existedFile . ']' . PHP_EOL . ' What would you like to do? ', [1 => 'copy existed key', 2 => 'generate new key', 3 => 'exit', 4 => 'skip'], 'copy existed key');

                if ($choice == 'exit') {
                    return self::FAILURE;
                }

                if ($choice == 'skip') {
                    continue;
                }

                $this->putEnvKey($env, $choice == 'copy existed key' ? $existedKey : $key, $choice == 'copy existed key' ? 'copied' : 'generated');
            } else {
                $this->putEnvKey($env, $key, 'generated');
            }
        }

        return self::SUCCESS;
    }

    private function putEnvKey(string $file, $key, string $operation)
    {
        $file = Path::join(DIR_ROOT, $file);

        $content = FileSystem::fread($file);

        $normalized = str_replace(["\r\n", "\r"], "\n", $content);

        $lines = array_filter(explode(PHP_EOL, $normalized), function ($line) {
            return !str_starts_with(trim($line), 'APP_KEY=');
        });

        $lastAppGroupEnv = $this->getLastAppGroupEnv($lines);

        if ($lastAppGroupEnv === null) {
            $lines[] = 'APP_KEY=' . $key;
        } else {
            array_splice($lines, $lastAppGroupEnv + 1, 0, 'APP_KEY=' . $key);
        }

        if (count($lines) > 0 && empty($lines[0])) {
            unset($lines[0]); // remove first empty line
        }

        file_put_contents($file, implode(PHP_EOL, $lines) . PHP_EOL);

        $this->io->box('Success', 'white', 'green', ['bold'], true);
        $this->io->success(" [$file] 🠆 secret key $operation successfully", true, ' ✓');
        $this->io->newLine();
    }

    private function getLastAppGroupEnv($lines): ?int
    {
        $last = null;

        foreach ($lines as $index => $line) {
            if (str_starts_with($line, 'APP_')) {
                $last = $index;
            }
        }

        return $last;
    }

    private function isSecretKeyAlreadyExists(string $env): bool
    {
        $env = Env::create(name: $env);

        return $env->has('APP_KEY') && !empty($env->get('APP_KEY'));
    }

    private function getSecretKey(string $env): string
    {
        $env = Env::create(name: $env);

        return $env->get('APP_KEY');
    }
}
