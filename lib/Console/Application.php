<?php

namespace NovaFrame\Console;

use NovaFrame\Facade\Event;
use NovaFrame\Kernel;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct(
        private readonly CommandLoader $loader
    )
    {
        parent::__construct(Kernel::NAME, Kernel::VERSION);

        Event::emit('console.start');

        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        foreach ($this->loader->load() as $command) {
            $this->add($command);
        }

        // load framework commands at last to avoid overriding
        $frameworkCommands = \NovaFrame\Console\CommandRegistry::$commands;

        if (!empty($frameworkCommands)) {
            foreach ($frameworkCommands as $command) {
                $this->add(new $command());
            }
        }
    }
}
