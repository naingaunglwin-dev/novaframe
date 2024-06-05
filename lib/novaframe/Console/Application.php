<?php

namespace Nova\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Application extends SymfonyApplication
{
    public function __construct($start)
    {
        parent::__construct('NovaFrame', app()->version());

        $dispatcher = new EventDispatcher();

        $dispatcher->addSubscriber(new NOVAListener($start));

        $this->setDispatcher($dispatcher);
    }

    /**
     * Load the application commands
     *
     * @return void
     */
    public function commandLoader(): void
    {
        $kernel = new Kernel();

        foreach ($kernel->commands as $command) {
            $this->add(new $command());
        }

        $path      = config('app.paths.command');
        $namespace = config('app.namespace.command');

        if (!str_ends_with($namespace, '\\')) {
            $namespace .= '\\';
        }

        $files = scandir($path);

        $files = $this->skipFiles($files, ['.', '..', '.gitkeep']);

        if (!empty($files)) {

            foreach ($files as $file) {
                $name = pathinfo($file, PATHINFO_FILENAME);

                $extension = pathinfo($file, PATHINFO_EXTENSION);

                if ($extension == 'php') {
                    $class = $namespace . $name;
                    $class = new $class();

                    if ($class instanceof Command) {
                        $this->add($class);
                    }
                }
            }
        }
    }

    /**
     * Skip specified files from the array.
     *
     * @param array $file The array of file names.
     * @param array $skip The list of file names to skip.
     * @return array The filtered array of file names.
     */
    private function skipFiles(array $file, array $skip): array
    {
        return array_diff($file, $skip);
    }

    /**
     * Get the current called command
     *
     * @return string|null
     */
    public static function getCurrentCommand(): ?string
    {
        return (new self(microtime(true)))->getCommandName(new ArgvInput());
    }
}
