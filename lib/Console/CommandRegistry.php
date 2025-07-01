<?php

namespace NovaFrame\Console;

class CommandRegistry
{
    public static array $commands = [
        \NovaFrame\Console\Commands\CreateController::class,
        \NovaFrame\Console\Commands\CreateMiddleware::class,
        \NovaFrame\Console\Commands\CreateModel::class,
        \NovaFrame\Console\Commands\CreateCommand::class,
        \NovaFrame\Console\Commands\Serve::class,
        \NovaFrame\Console\Commands\GenerateSecretKey::class,
        \NovaFrame\Console\Commands\DisplayApplicationEnv::class,
        \NovaFrame\Console\Commands\DisplayRegisteredRoutes::class,
        \NovaFrame\Console\Commands\Migration::class,
        \NovaFrame\Console\Commands\MigrationRollback::class,
        \NovaFrame\Console\Commands\MigrationRefresh::class,
        \NovaFrame\Console\Commands\CreateMigration::class,
        \NovaFrame\Console\Commands\Seed::class,
        \NovaFrame\Console\Commands\CreateSeeder::class,
        \NovaFrame\Console\Commands\ClearSession::class,
        \NovaFrame\Console\Commands\StorageLink::class,
        \NovaFrame\Console\Commands\StorageUnlink::class,
    ];
}
