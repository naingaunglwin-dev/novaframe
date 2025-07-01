<?php

declare(strict_types=1);

namespace NovaFrame\Container;

use NovaFrame\Container\Exceptions\AbstractNotFound;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionUnionType;

class Container
{
    /**
     * Singleton instance of the container.
     *
     * @var Container|null
     */
    private static ?Container $instance;

    /**
     * Array of shared (singleton) bindings.
     *
     * @var array<string, mixed>
     */
    private array $shared = [];

    /**
     * Array of abstract-to-concrete definitions.
     *
     * @var array<string, string|object>
     */
    private array $definitions = [];

    /**
     * List of shared instances that have already been resolved.
     *
     * @var array<string>
     */
    private array $resolvedShared = [];

    /**
     * Container constructor
     */
    public function __construct()
    {
    }

    /**
     * Bind a concrete implementation to an abstract.
     *
     * @param string $abstract
     * @param string|object|null $concrete
     * @param bool $overwrite Whether to overwrite existing binding.
     * @return static
     */
    public function add(string $abstract, null|string|object $concrete = null, bool $overwrite = false): static
    {
        $concrete ??= $abstract;

        if ($overwrite && isset($this->definitions[$abstract])) {
            unset($this->definitions[$abstract]);
        }

        $this->definitions[$abstract] = $concrete;

        return $this;
    }

    /**
     * Register a singleton (shared) binding
     *
     * @param string $abstract
     * @param string|object|null $concrete
     * @param bool $overwrite Whether to overwrite existing binding.
     * @return static
     */
    public function singleton(string $abstract, null|string|object $concrete = null, bool $overwrite = false)
    {
        $this->add($abstract, $concrete, $overwrite);

        $concrete ??= $abstract;

        $this->shared[$abstract] = $concrete;

        return $this;
    }

    /**
     * Resolve a dependency
     * 
     * Handles:
     * - Class instantiation
     * - Class method resolution
     * - Callables with parameter injection
     *
     * @param mixed ...$params
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function get(...$params)
    {
        switch (func_num_args()) {
            // for class instance or callback functions
            case 1:
                return $this->resolveUponType(func_get_arg(0));
            case 2:
                if (is_array(func_get_arg(1))) {  // for class instance or callback functions with explicit params
                    $concrete = func_get_arg(0);
                    $args     = func_get_arg(1) ?: [];
                    return $this->resolveUponType($concrete, $args);
                } else { // for class's method resolve
                    $abstract = func_get_arg(0);
                    $method   = func_get_arg(1);

                    return $this->resolveMethod($abstract, $method);
                }

            // for class's method resolve with explicit params
            case 3:
                $abstract = func_get_arg(0);
                $method   = func_get_arg(1);
                $args     = func_get_arg(2) ?: [];

                return $this->resolveMethod($abstract, $method, $args);

            default:
                throw new \InvalidArgumentException("Invalid parameters usage");
        }
    }

    /**
     * Resolve a registered binding.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     *
     * @throws AbstractNotFound
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (!isset($this->definitions[$abstract])) {
            throw new AbstractNotFound($abstract);
        }

        return $this->resolve($abstract, $parameters);
    }

    /**
     * Determine if a binding exists.
     *
     * @param string $abstract
     * @return bool
     */
    public function has(string $abstract): bool
    {
        return isset($this->definitions[$abstract]);
    }

    /**
     * Resolve a class or shared binding.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    private function resolve(string $abstract, array $parameters = [])
    {
        if (isset($this->shared[$abstract])) {

            if (!$this->isResolved($abstract)) {
                $concrete = $this->shared[$abstract];

                $this->shared[$abstract] = $this->resolveUponType($concrete, $parameters);

                $this->markedAsResolved($abstract);
            }

            return $this->shared[$abstract];
        }

        return $this->resolveUponType($this->definitions[$abstract], $parameters);
    }


    /**
     * Resolve an object, class, or callable.
     *
     * @param mixed $concrete
     * @param array $parameters
     * @return mixed
     */
    private function resolveUponType($concrete, array $parameters = [])
    {
        // If it's an object, check if it's a Closure or has __invoke
        if (is_object($concrete)) {
            if ($concrete instanceof \Closure || method_exists($concrete, '__invoke')) {
                return $this->resolveCallable($concrete, $parameters);  // It's a callable object (Closure or object with __invoke)
            }
            return $this->resolveClass($concrete, $parameters);  // It's a regular class object
        }

        // If it's a string, check if it's a class name or a callable function (like 'function_name' or 'ClassName::method')
        if (is_string($concrete)) {
            if (class_exists($concrete)) {
                return $this->resolveClass($concrete, $parameters);  // It's a class name
            }

            // Check if it's a function or method call
            if (function_exists($concrete) || str_contains($concrete, '::')) {
                return $this->resolveCallable($concrete, $parameters);  // It's a callable function or static method
            }
        }

        // If it's a callable (like [$object, 'method'])
        if (is_callable($concrete)) {
            return $this->resolveCallable($concrete, $parameters);  // It's a general callable (function, method, etc.)
        }

        // Default fallback: handle any other case as a class name
        return $this->resolveClass($concrete, $parameters);
    }

    /**
     * Check if a singleton binding is already resolved.
     *
     * @param string $abstract
     * @return bool
     */
    private function isResolved(string $abstract): bool
    {
        return in_array($abstract, $this->resolvedShared);
    }

    /**
     * Resolve and instantiate a class.
     *
     * @param string|object $concrete
     * @param array $parameters
     * @return object
     */
    private function resolveClass(string|object $concrete, array $parameters = [])
    {
        if (is_object($concrete)) {
            return $concrete;
        }

        if (!class_exists($concrete)) {
            throw new \InvalidArgumentException('Class ' . $concrete . ' does not exist.');
        }

        $reflection  = new \ReflectionClass($concrete);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $concrete(); // just create a new instance if class doesn't have constructor
        }

        return new $concrete(...$this->resolveDependencies($constructor->getParameters(), $parameters));
    }

    /**
     * Resolve and call a method of a class.
     *
     * @param string|object $concrete
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    private function resolveMethod(string|object $concrete, string $method, array $parameters = [])
    {
        if (!method_exists($concrete, $method)) {
            throw new \InvalidArgumentException('Method ' . $concrete . ' does not exist.');
        }

        $reflection = new \ReflectionMethod($concrete, $method);

        $name = $reflection->getName();

        $instance = $this->resolveClass($concrete);

        return $instance->{$name}(...$this->resolveDependencies($reflection->getParameters(), $parameters));
    }

    /**
     * Resolve and invoke a callable.
     *
     * @param callable|string $concrete
     * @param array $parameters
     * @return mixed
     */
    private function resolveCallable($concrete, array $parameters = [])
    {
        $callback = $concrete;

        if (is_string($concrete)) {
            $callback = $this->definitions[$concrete];
        }

        return $callback(...$this->resolveDependencies(
            (new \ReflectionFunction($callback))->getParameters(), $parameters
        ));
    }

    /**
     * Resolve dependencies for a callable or constructor.
     *
     * @param array $builtInParameters
     * @param array $explicitParameters
     * @return array
     */
    private function resolveDependencies(array $builtInParameters, array $explicitParameters = []): array
    {
        $resolvedDependencies = [];

        foreach ($builtInParameters as $builtInParameter) {
            /**
             * @type $builtInParameter \ReflectionParameter
             */
            $name = $builtInParameter->getName();
            $type = $builtInParameter->getType();

            if ($builtInParameter->isOptional()) {
                continue;
            }

            if (array_key_exists($name, $explicitParameters)) {
                $resolvedDependencies[] = $explicitParameters[$name]; // skip to resolve if param exists in given param list by user
                continue;
            }

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencyClass = $type->getName();

                if (isset($this->shared[$dependencyClass])) {
                    // If not yet resolved, resolve and cache it
                    if (!$this->isResolved($dependencyClass)) {
                        $concrete = $this->shared[$dependencyClass];
                        $this->shared[$dependencyClass] = $this->resolveUponType($concrete);
                        $this->markedAsResolved($dependencyClass);
                    }

                    $resolvedDependencies[] = $this->shared[$dependencyClass];
                } else {
                    $resolvedDependencies[] = $this->resolveClass($dependencyClass);
                }
                continue;
            }

            if ($type instanceof ReflectionUnionType || $type instanceof ReflectionIntersectionType) {

                foreach ($type->getTypes() as $innerType) {
                    if ($innerType instanceof ReflectionNamedType && !$innerType->isBuiltin()) {
                        $dependencyClass = $innerType->getName();
                        if (isset($this->shared[$dependencyClass])) {
                            // If not yet resolved, resolve and cache it
                            if (!$this->isResolved($dependencyClass)) {
                                $concrete = $this->shared[$dependencyClass];
                                $this->shared[$dependencyClass] = $this->resolveUponType($concrete);
                                $this->markedAsResolved($dependencyClass);
                            }

                            $resolvedDependencies[] = $this->shared[$dependencyClass];
                        } else {
                            $resolvedDependencies[] = $this->resolveClass($dependencyClass);
                        } // resolve manually if param isn't built in

                        continue 2;
                    }
                }
            }

            if ($builtInParameter->isDefaultValueAvailable()) {
                $resolvedDependencies[] = $builtInParameter->getDefaultValue();
                continue;
            }

            throw new \RuntimeException("Cannot resolve dependency \${$name}");
        }

        return $resolvedDependencies;
    }

    /**
     * Mark a shared binding as resolved.
     *
     * @param string $abstract
     * @return void
     */
    private function markedAsResolved(string $abstract): void
    {
        $this->resolvedShared[] = $abstract;
    }

    /**
     * Get the singleton instance of the container.
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function getInstance(...$parameters): static
    {
        if (!isset(static::$instance)) {
            static::$instance = new static(...$parameters);
        }

        return static::$instance;
    }
}
