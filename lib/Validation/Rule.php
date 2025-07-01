<?php

namespace NovaFrame\Validation;

use NovaFrame\Helpers\Path\Path;
use NovaFrame\Validation\Rules\Form;

class Rule
{
    /**
     * @var array<class-string>
     */
    private array $defaults = [
        Form::class
    ];

    public function discover(): array
    {
        $discovered = [];

        foreach ($this->defaults as $default) {
            $reflection = new \ReflectionClass($default);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() !== $default) {
                    continue;
                }

                $name = $method->getName();

                if (str_starts_with($name, '__')) {
                    continue;
                }

                $discovered[$name] = $reflection->getName();
            }
        }

        $path = Path::join(DIR_APP, 'Validations');

        if (!is_dir($path)) {
            return $discovered;
        }

        $validations = glob($path . DS . '*.php');

        if (empty($validations)) {
            return [];
        }

        foreach ($validations as $validation) {
            $classname = basename($validation, '.php');
            $classname = "\\App\\Validations\\{$classname}";

            $class = app()->get($classname);

            if (!$class instanceof Validation) {
                throw new \RuntimeException('Validation class ' . $class::class . ' must implement to ' . Validation::class);
            }

            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() !== $classname) {
                    continue;
                }

                $name = $method->getName();

                if (str_starts_with($name, '__')) { // skip magic methods
                    continue;
                }

                $discovered[$name] = $reflection->getName();
            }
        }

        return $discovered;
    }
}
