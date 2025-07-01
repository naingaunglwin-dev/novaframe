<?php

namespace NovaFrame\Database;

use NovaFrame\Console\ConsoleStyle;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\RuntimeEnv;

class Seed
{
    public function __construct(private ?ConsoleStyle $io = null)
    {
    }

    public function run(?string $specific = null)
    {
        if (RuntimeEnv::envIs('cli')) {
            $this->io->box('Seeding', 'white', 'cyan', newline: true);
            $this->io->comment('Run data seeding...', true);
        }

        $seeders = [];

        if ($specific) {
            $file = Path::join(DIR_APP, 'Database', 'Seeds', $specific . '.php');

            if (!file_exists($file)) {
                throw new \InvalidArgumentException("Seed file '$file' not found");
            }

            $seeders[] = $file;
        } else {
            $seeders = $this->discover();
        }

        if (empty($seeders)) {
            return;
        }

        foreach ($seeders as $seeder) {
            $classname = $classname = pathinfo($seeder, PATHINFO_FILENAME);
            $class = "App\\Database\\Seeds\\$classname";

            require_once $seeder;
            $class = new $class();

            if (!$class instanceof Seeder) {
                throw new \RuntimeException("Seed class must extend to " . Seeder::class);
            }

            $class->run();

            if (RuntimeEnv::envIs('cli')) {
                $this->io->success(' ['. $class::class . '] seeded.', true, ' ✓');
            }
        }

        if (RuntimeEnv::envIs('cli')) {
            $this->io->newLine();
            $this->io->success(' Seeding completed!', true, ' ✓');
        }
    }

    public function discover()
    {
        $path = Path::join(DIR_APP, 'Database', 'Seeds');

        $files = glob($path . '/*.php');

        return empty($files) ? [] : $files;
    }
}
