<?php

namespace Nova\Console\DefaultCommands\Traits;

use Nova\Helpers\Modules\File;

/**
 * Trait ProcessResolver
 *
 * This trait provides methods for resolving and processing various types of files,
 * such as controllers, migrations, seeders, commands, middleware, and models.
 *
 */
trait ProcessResolver
{
    /**
     * Resolve the given file type.
     *
     * This method resolves the given file type by checking if it already exists and prompts the user for confirmation
     * to overwrite if it does. It then generates the file with the provided content and namespace.
     *
     * @param string $name The name of the file type to resolve.
     * @return int Returns SUCCESS if the file is successfully generated, otherwise FAILURE.
     */
    protected function resolve(string $name): int
    {
        if ($name === 'command') {
            $arg = ucfirst($name);
        } else {
            $arg = $name;
        }

        $file = $this->getArgument($arg);

        $table = null;

        if ($name === 'model') {
            $table = $this->getArgument('table');
        }

        $namespace = config("app.namespace.{$name}");

        if (!str_ends_with($namespace, '\\')) {
            $namespace = $namespace . '\\';
        }

        $check = $namespace . $file;

        if (class_exists($check)) {
            $this->box('Warning', 'white', 'bright-yellow', ['bold']);
            $this->warning("$check.php is already exist.", [], true);

            if (!$this->confirm('Do you want to replace it, all content will be overwrite?')) {
                return self::FAILURE;
            }
        }

        [$class, $classNamespace] = $this->namespaceResolver($file, $namespace);

        $content = $this->contentReplace($name, $classNamespace, $class, $table);

        $path = config("app.paths.{$name}");

        try {
            $helper = new File();

            $helper->setFile($path . DIRECTORY_SEPARATOR . $file . '.php');

            if ($helper->writeContent($content)) {
                $dir = str_replace(APP_PATH, '', $path);

                $this->box('Success', 'white', 'green', ['bold']);
                $this->info(" APPPATH/$dir/$file.php created successfully.", true);

                return self::SUCCESS;
            } else {

                $this->box('Error', 'white', 'red', ['bold']);
                $this->message("Fail to generate $name file", 'red', [], true);

                return $this::FAILURE;
            }
        } catch (\Exception $exception) {
            throw new \RuntimeException($exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
        }

    }

    /**
     * Resolve the namespace and class name from the given file path.
     *
     * This method resolves the namespace and class name from the given file path.
     *
     * @param string $file The file path.
     * @param string $namespace The base namespace.
     * @return array An array containing the resolved class name and namespace.
     */
    protected function namespaceResolver(string $file, string $namespace): array
    {
        $classArray = explode('/', $file);

        $className = '';
        $classNamespaceFromArg = '';

        if (isset($classArray[array_key_last($classArray)]) && $classArray[array_key_last($classArray)] >= 1) {
            $className = $classArray[array_key_last($classArray)];
            $classNamespaceFromArg = str_replace('/', '\\', $file);
            $classNamespaceFromArg = substr($classNamespaceFromArg, 0, strrpos($classNamespaceFromArg, '\\'));
        }

        $className = $className ?? $file;

        $classNamespace = $classNamespaceFromArg ? $namespace . $classNamespaceFromArg : substr($namespace, 0, -1);

        if (str_starts_with($classNamespace, '\\')) {
            $classNamespace = substr($classNamespace, 1);
        }

        return [$className, $classNamespace];
    }

    /**
     * Replace placeholders in the file content with actual values.
     *
     * This method replaces placeholders in the file content with actual values such as namespace and class name.
     *
     * @param string $file The type of file being processed.
     * @param string $namespace The namespace to use.
     * @param string $class The class name.
     * @param string|null $table The table name (optional, used for migrations).
     * @return string The processed file content.
     */
    protected function contentReplace(string $file, string $namespace, string $class, string $table = null): string
    {
        preg_match(
            '/<<<EOT\s*(.*?)\s*EOT;/s',
            file_get_contents(NOVA_PATH . 'Console' . DIRECTORY_SEPARATOR . 'DefaultCommands' . DIRECTORY_SEPARATOR . 'Contents' . DIRECTORY_SEPARATOR . $file . '.php'),
            $matches
        );

        $content = $matches[1];

        $content = sprintf($content, $namespace, $class, $table ?? '');

        $content .= "\n";

        return $content;
    }
}
