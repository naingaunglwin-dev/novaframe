<?php

namespace Nova\Console;

class Kernel
{
    public array $commands = [
        \Nova\Console\DefaultCommands\DisplayCurrentEnv::class,
        \Nova\Console\DefaultCommands\DisplayDefinedRoutes::class,
        \Nova\Console\DefaultCommands\GenerateSecretKey::class,
        \Nova\Console\DefaultCommands\CacheClear::class,
        \Nova\Console\DefaultCommands\CreateController::class,
        \Nova\Console\DefaultCommands\CreateMiddleware::class,
        \Nova\Console\DefaultCommands\CreateModel::class,
        \Nova\Console\DefaultCommands\CreateCommand::class,
        \Nova\Console\DefaultCommands\Serve::class,
    ];
}
