<?php

namespace Nova\Event;

interface EventInterface
{
    /**
     * Register an event listener
     *
     * @param string $event The name of the event to listen for
     * @param callable $callback The callback function to execute when the event is triggered
     * @return void
     */
    public static function on(string $event, callable $callback): void;

    /**
     * Trigger an event
     *
     * @param string $event The name of the event to trigger
     * @param mixed ...$params Parameters to pass to the event listeners
     * @return void
     */
    public static function trigger(string $event, mixed ...$params): void;

    /**
     * Get all listeners for a given event
     *
     * @param string $event The name of the event
     * @return mixed The array of listeners for the event, or null if the event has no listeners.
     */
    public static function getListeners(string $event): mixed;

    /**
     * Remove all listeners for a given event.
     *
     * @param string $event The name of the event
     * @return void
     */
    public static function removeListeners(string $event): void;

    /**
     * To load events on application system on `before` or `after`
     *
     * @param string $state
     * @param callable $callback
     * @return void
     */
    public static function system(string $state, callable $callback): void;
}
