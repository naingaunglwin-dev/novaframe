<?php

namespace NovaFrame\Pipeline;

use NovaFrame\Container\Container;
use NovaFrame\Pipeline\Exceptions\InvalidPipe;

class Pipeline
{
    /**
     * Array of pipes (callables, class names, or strings) through which
     * the passables are sent.
     *
     * @var array
     */
    private array $pipes = [];

    /**
     * Parameters passed through the pipeline.
     *
     * @var array
     */
    private array $passables = [];

    /**
     * Set the parameters that will be passed through the pipeline.
     *
     * @param mixed ...$passables One or more parameters to pass through the pipes.
     * @return $this
     */
    public function send(...$passables): Pipeline
    {
        $this->passables = $passables;

        return $this;
    }

    /**
     * Define the pipes (middlewares) through which the passables will be sent.
     *
     * @param array|string|callable $pipes Array or single pipe(s).
     * @return $this
     */
    public function through(array|string|callable $pipes): Pipeline
    {
        $this->pipes = is_array($pipes) ? $pipes : [$pipes];

        return $this;
    }

    /**
     * Execute the pipeline and then call the destination callback.
     *
     * Pipes are executed in reverse order of addition, each calling the next.
     * Pipes can be:
     * - callable functions with signature fn($passable, $next)
     * - class names with a `handle` method accepting ($passable, $next)
     *
     * @param callable $destination The final callback to call after all pipes.
     * @return mixed The return value of the final callback or last pipe.
     *
     * @throws InvalidPipe If any pipe is not callable or valid.
     * @throws \RuntimeException If a pipe class does not have a 'handle' method.
     */
    public function then(callable $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            function ($next, $pipe) {
                return function ($args) use ($next, $pipe) {

                    if (is_callable($pipe)) {
                        return $pipe($args, $next);
                    }

                    if (is_string($pipe) && class_exists($pipe)) {
                        $instance = (new Container())->get($pipe);

                        if (!method_exists($instance, 'handle')) {
                            throw new \RuntimeException("Can not find method 'handle' in {$pipe}");
                        }

                        $params = [$args, $next];

                        return $instance->handle(...$params);
                    }

                    throw new InvalidPipe($pipe);
                };
            },
            $destination
        );

        return $pipeline(...$this->passables);
    }
}
