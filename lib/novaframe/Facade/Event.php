<?php

namespace Nova\Facade;

use Nova\Facade\Facade;

/**
 * @method static \Nova\Event\Event on(string $event, callable $listener, int $priority = 0) Register an event listener
 * @method static \Nova\Event\Event once(string $event, callable $listener, int $priority = 0) Register a one-time event listener
 * @method static \Nova\Event\Event getListeners(string $event = null) Get all the listeners for a given event or all of listeners
 * @method static \Nova\Event\Event removeListeners(string $event = null) Remove all listeners for a given event
 * @method static \Nova\Event\Event removeListener(string $event, callable $listener) Remove the given listener from given event
 * @method static \Nova\Event\Event emit(string $event, mixed ...$args) Emit an event
 */
class Event extends Facade
{
    /**
     * Define the fully qualified class name that this facade represents.
     *
     * @return string
     */
    protected static function defineClass(): string
    {
        return \Nova\Event\Event::class;
    }

    /**
     * Determine if the underlying class should be treated as a singleton.
     *
     * @return bool
     */
    protected static function singleton(): bool
    {
        return true;
    }
}
