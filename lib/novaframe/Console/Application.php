<?php

namespace Nova\Console;

use Nova\Facade\Event;
use Nova\File\FileCollection;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Application extends SymfonyApplication
{
    public function __construct($start)
    {
        parent::__construct('NovaFrame', app()->version());

        Event::emit("console_start", new ConsoleOutput, date("Y-m-d H:i:s"), config('app.timezone'));

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
        $path      = config('app.paths.command');
        $namespace = config('app.namespace.command');

        if (!str_ends_with($namespace, '\\')) {
            $namespace .= '\\';
        }

        if (!str_ends_with($path, "\\") && !str_ends_with($path, "/")) {
            $path = $path . "/*";
        }

        $files = fc()->from($path);

        if ($files instanceof FileCollection) {
            $files->each(function ($file) use ($namespace) {
                $file = f($file);
                if ($file->extension() === "php") {
                    $class = $namespace . $file->name();
                    $class = new $class();

                    if ($class instanceof Command) {
                        $this->add($class);
                    }
                }
            });
        }

        $kernel = new Kernel();

        foreach ($kernel->commands as $command) {
            $this->add(new $command());
        }
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
