<?php

namespace NovaFrame\Database;

use NovaFrame\Console\ConsoleStyle;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\RuntimeEnv;

class Migrate
{
    private Database $db;

    private MigrationTable $table;

    const MODE_UP = 'up';

    const MODE_DOWN = 'down';

    const MODE_REFRESH = 'refresh';

    public function __construct(private ?ConsoleStyle $io = null)
    {
        $this->db = new Database(config('database'));
        $this->table = new MigrationTable($this->db);
    }

    public function run(string $mode, ?int $version = null, bool $dropAll = false)
    {
        if (!in_array($mode, [self::MODE_UP, self::MODE_DOWN, self::MODE_REFRESH])) {
            return;
        }

        $migrations = $this->discover();

        if (empty($migrations)) {
            return;
        }

        match ($mode) {
            self::MODE_UP => $this->up($migrations),
            self::MODE_DOWN => $this->down($version, $dropAll),
            self::MODE_REFRESH => $this->refresh($migrations),
        };
    }

    private function refresh(array $migrations)
    {
        $this->down(dropAll: true);

        $this->up($migrations);

        if (RuntimeEnv::envIs('cli')) {
            $this->io->success(' migration refreshed!', true, ' ✓');
        }
    }

    private function up(array $migrations)
    {
        if (RuntimeEnv::envIs('cli')) {
            $this->io->box('Migrate', 'white', 'cyan', newline: true);
            $this->io->comment('Running migration...', true);
        }

        $migrations = array_diff($migrations, $this->getMigratedFiles());

        if (empty($migrations)) {
            return;
        }

        $lastVersion = $this->table->getLastVersion() ?? 0;

        foreach ($migrations as $migration) {

            /** @var Migration $class */
            $class = include $migration;

            $sql = $class->up();

            if ($sql instanceof TableSchema) {
                $sql = $sql->build()->toSql();
            }

            $result = $this->db->execute($sql);

            if ($result) {
                $this->table->save($migration, $lastVersion + 1);
            }

            if (RuntimeEnv::envIs('cli')) {
                if ($result) {
                    $this->io->success(' [' . basename($migration) . '] migrated', true, ' ✓');
                } else {
                    $this->io->error(' [' . basename($migration) . '] fail to migrate', true, ' 🞩');
                }
            }
        }

        if (RuntimeEnv::envIs('cli')) {
            $this->io->newLine();
            $this->io->success(' Migration completed!', true, ' ✓');
            $this->io->newLine(2);
        }
    }

    private function down(?int $version = null, bool $dropAll = false)
    {
        if (RuntimeEnv::envIs('cli')) {
            $this->io->box('Rollback', 'white', 'cyan', newline: true);
            $this->io->comment('Running migration rollback...', true);
        }

        if (empty($this->getMigratedFiles())) {
            return;
        }

        $migrations = $this->getMigratedFileToDrop($version, $dropAll);

        if (empty($migrations)) {
            return;
        }

        $migrations = array_reverse($migrations);

        foreach ($migrations as $migration) {
            $class = include $migration;
            $tableSchema = $class->down();

            $result = $this->db->execute($tableSchema->build()->toSql());

            if ($result) {
                $this->table->delete($migration);
            }

            if (RuntimeEnv::envIs('cli')) {
                if ($result) {
                    $this->io->success(' [' . basename($migration) . '] rollback', true, ' ✓');
                } else {
                    $this->io->error(' [' . basename($migration) . '] fail to rollback', true, ' 🞩');
                }
            }
        }

        if (RuntimeEnv::envIs('cli')) {
            $this->io->newLine();
            $this->io->success(' Migration rollback completed!', true, ' ✓');
            $this->io->newLine(2);
        }
    }

    private function getMigratedFileToDrop(?int $version = null, $dropAll = false)
    {
        if ($dropAll) {
            return $this->getMigratedFiles();
        }

        $version = $version ?? $this->table->getLastVersion();

        if (empty($version)) {
            return [];
        }

        return $this->table->getMigrations($version);
    }

    private function getMigratedFiles(): array
    {
        return $this->table->getMigrations();
    }

    private function discover(): array
    {
        $path = Path::join(DIR_APP, 'Database', 'Migrations');

        $files = glob($path . '/*.php');

        return empty($files) ? [] : $files;
    }
}
