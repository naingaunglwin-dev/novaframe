<?php

namespace Nova\Container;

class Container
{
    /**
     * @var Container|null
     */
    private static ?Container $instance;

    /**
     * An array to hold instances of resolved bindings
     *
     * @var array
     */
    private array $instances = [];

    /**
     * An array to hold binding definitions
     *
     * @var array
     */
    private array $bindings = [];

    /**
     * Register a singleton binding with the container
     *
     * @param string      $abstract The abstract type or class name
     * @param string|callable|null $factory The concrete class
     *
     * @return void
     */
    public function singleton(string $abstract, string|callable $factory = null): void
    {
        $this->bind($abstract, $factory, true);
    }

    /**
     * Register a normal binding with the container
     *
     * @param string      $abstract The abstract type or class name
     * @param string|callable|null $factory The concrete class
     *
     * @return void
     */
    public function add(string $abstract, string|callable $factory = null): void
    {
        $this->bind($abstract, $factory);
    }

    /**
     * Bind a concrete implementation or factory function to an abstract type in the container
     *
     * @param string      $abstract
     * @param string|callable|null $factory
     * @param bool        $share
     *
     * @return void
     */
    private function bind(string $abstract, string|callable $factory = null, bool $share = false): void
    {
        $this->bindings[$abstract]['share']   = $share;
        $this->bindings[$abstract]['factory'] = $factory ?? $abstract;
    }

    /**
     * Check if the specified abstract type is instantiable (resolved) in the container
     *
     * @param string $abstract The abstract type or class name
     *
     * @return bool true if the abstract is instantiable, otherwise false
     */
    private function isInstantiable(string $abstract): bool
    {
        return isset($this->instances[$abstract]);
    }

    /**
     * Set the instance of the specified abstract type in the container.
     *
     * @param string      $abstract The abstract type or class name
     * @param string|null $factory The concrete class
     *
     * @return void
     */
    private function setInstance(string $abstract, string $factory = null): void
    {
        if (!$this->isInstantiable($abstract)) {
            $this->instances[$abstract] = $factory ?? $abstract;
        }
    }

    /**
     * Check if a binding exists for the specified abstract type in the container
     *
     * @param string $abstract The abstract type or class name
     *
     * @return bool true if a binding exists, otherwise false
     */
    private function isBindingExists(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }

    /**
     * Get the binding definition for the specified abstract type in the container.
     *
     * @param string $abstract The abstract type or class name.
     *
     * @return mixed The binding definition.
     */
    private function getBindingOfAbstract(string $abstract): mixed
    {
        return $this->bindings[$abstract];
    }

    /**
     * Get the instance of the specified abstract type from the container.
     *
     * @param string $abstract The abstract type or class name.
     *
     * @return mixed The instance of the abstract type.
     */
    private function getInstanceOfAbstract(string $abstract): mixed
    {
        return $this->instances[$abstract];
    }

    /**
     * Resolve the specified abstract type from the container.
     *
     * @param string $abstract The abstract type or class name to resolve.
     * @param mixed ...$parameters Optional parameters to pass to the resolved class constructor.
     * @return mixed The resolved instance or factory function.
     * @throws \InvalidArgumentException If the abstract does not exist in the container.
     */
    public function make(string $abstract, mixed ...$parameters): mixed
    {
        if (!$this->isBindingExists($abstract)) {
            throw new \InvalidArgumentException("{$abstract} class does not exist in Container");
        }

        $bind = $this->getBindingOfAbstract($abstract);

        if ($bind['share'] === true) {
            $this->setInstance($abstract, $bind['factory']);
        }

        if ($bind['share'] === true) {
            if (!is_callable($this->getInstanceOfAbstract($abstract))) {
                $factory = function () use ($abstract, $parameters) {
                    return new $this->instances[$abstract](...$parameters);
                };
            } else {
                $factory = $this->getInstanceOfAbstract($abstract);
            }
        } else {
            if (!is_callable($bind['factory'])) {
                $factory = $bind['factory'];
                $factory = function () use ($factory, $parameters) {
                    return new $factory(...$parameters);
                };
            } else {
                $factory = $bind['factory'];
            }
        }

        return call_user_func($factory, ...$parameters);
    }

    /**
     * Get the singleton instance of the Container.
     *
     * @return mixed The instance of the static or instance of abstract class.
     */
    public static function getInstance(string $abstract = null, ...$parameters): mixed
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        if (!empty($abstract)) {
            return self::$instance->make($abstract, ...$parameters);
        }

        return self::$instance;
    }
}
