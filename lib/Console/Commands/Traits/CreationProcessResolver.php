<?php

namespace NovaFrame\Console\Commands\Traits;

use Doctrine\Inflector\InflectorFactory;
use NovaFrame\Helpers\FileSystem\FileSystem;
use NovaFrame\Helpers\Path\Path;

trait CreationProcessResolver
{
    protected function resolve(string $command)
    {
        $arg = $command === 'command' ? ucfirst($command) : $command;

        $file = $this->input->getArgument($arg);

        if (empty($file)) {
            $file = $this->ask("Please enter $command name");

            if (empty($file)) {
                $this->box('Error', 'white', 'red', ['bold'], newline: true);
                $this->io->error(' You must provide a valid name.', emoji: ' 🞩');

                return self::FAILURE;
            }
        }
        
        $file = ucfirst(trim($file));
        
        $table = null;
        
        if ($command === 'model') {
            $table = $this->input->getArgument('table');

            if (empty($table)) {
                $table = $this->ask("Please enter table name");

                if (empty($table)) {
                    $table = ucfirst(
                        InflectorFactory::create()->build()->pluralize(strtolower($file))
                    );
                }
            }
        }

        $namespaces = [
            'controller' => "App\\Http\\Controllers\\",
            'model'      => "App\\Models\\",
            'command'    => "App\\Commands\\",
            'middleware' => "App\\Http\\Middlewares\\",
            'migration'  => "App\\Database\\Migrations\\",
            'seed'       => "App\\Database\\Seeds\\",
        ];

        $namespace = $namespaces[$command];

        $classname = $namespace . $file;

        if (class_exists($classname)) {
            $this->io->box('Warning', 'white', 'yellow', newline: true);
            $this->io->warning(" $classname is already exists.", true, ' ⚠');
            $this->io->newLine();

            if (!$this->confirm('All content will be overwritten, Do you want to continue?', false)) {
                return self::FAILURE;
            }
        }

        [$classname, $namespace] = $this->namespaceResolver($file, $namespace);

        $content = $this->getCompiledContent($command, $namespace, $classname, $table);

        if ($content == self::FAILURE) {
            return self::FAILURE;
        }

        $paths = [
            'controller' => Path::join(DIR_APP, 'Http', 'Controllers'),
            'model'      => Path::join(DIR_APP, 'Models'),
            'command'    => Path::join(DIR_APP, 'Commands'),
            'middleware' => Path::join(DIR_APP, 'Http', 'Middlewares'),
            'migration'  => Path::join(DIR_APP, 'Database', 'Migrations'),
            'seed'       => Path::join(DIR_APP, 'Database', 'Seeds'),
        ];

        $path = $paths[$command];

        try {
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            if ($command === 'migration') {
                $timestamp = config('migration.timestamp', 'Y-m-d_H-i-s_');

                if (!str_ends_with($timestamp, '_') && !str_ends_with($timestamp, '-')) {
                    $timestamp .= '_';
                }

                $file = date($timestamp) . $file;
            }

            $fullPath = Path::join($path, "$file.php");

            if (FileSystem::fwrite($fullPath, $content, true)) {
                $this->io->box('Success', 'white', 'green', ['bold'], true);
                $this->io->success(' ' . $fullPath . ' created successfully.', emoji: " ✓");
                $this->io->newLine();

                return self::SUCCESS;
            } else {
                $this->box('Error', 'white', 'red', ['bold']);
                $this->io->error(' Failed to create ' . $command, emoji: ' 🞩');
                $this->io->newLine();

                return self::FAILURE;
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . " in " . $e->getFile() . " at line " . $e->getLine(), $e->getCode(), $e);
        }
    }

    /**
     * Resolve the class name and namespace from a given file path and base namespace.
     *
     * @param string $file The relative file path (e.g. "Controllers/Auth/Login").
     * @param string $baseNamespace The base namespace to prefix (e.g. "App\\").
     * @return array An array with [class name, full namespace].
     */
    protected function namespaceResolver(string $file, string $baseNamespace): array
    {
        // Normalize path and convert to array
        $parts = array_filter(explode('/', trim($file, '/')));

        if ($parts === []) {
            // If file path is empty or malformed, return base namespace
            return [$file, rtrim($baseNamespace, '\\')];
        }

        // Extract the class name (last segment of the path)
        $className = array_pop($parts);

        // Convert remaining segments to namespace path
        $subNamespace = implode('\\', $parts);

        // Combine base and sub-namespace
        $fullNamespace = trim($baseNamespace . $subNamespace, '\\');

        return [$className, $fullNamespace];
    }

    protected function getCompiledContent(string $command, string $namespace, string $classname, ?string $table = null): string|int
    {
        $file = Path::join(DIR_NOVA, 'Console', 'Commands', 'stubs', "$command.php");

        if (!file_exists($file)) {
            $this->box('Error', 'white', 'red', ['bold']);
            $this->error('Stub file not found.');
            return self::FAILURE;
        }

        preg_match(
            '/<<<EOT\s*(.*?)\s*EOT;/s',
            file_get_contents($file),
            $matches
        );

        $content = $matches[1];

        if ($command === 'command') {
            $content = sprintf($content, $namespace, $classname, "run:" . strtolower($classname));
        } else {
            $content = sprintf($content, $namespace, $classname, $table ?? '');
        }

        $content .= "\n";

        return $content;
    }
}
