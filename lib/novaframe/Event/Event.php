<?php

namespace Nova\Event;

use InvalidArgumentException;
use Nova\Helpers\Modules\ResolveDependencies;

class Event
{
    /**
     * Registered event listeners
     *
     * @var array
     */
    private static array $listeners = [];

    /**
     * @var ResolveDependencies
     */
    private ResolveDependencies $resolver;

    /**
     * Event constructor
     */
    public function __construct()
    {
        $this->resolver = new ResolveDependencies();
    }

    /**
     * Register an event listener
     *
     * @param string $event The name of the event to listen for
     * @param callable $listener The callback function to execute when the event is emitted
     * @param int $priority Priority to sort the listeners
     * @return void
     */
    public function on(string $event, callable $listener, int $priority = 0): void
    {
        $this->addListener($event, $listener, $priority);
    }

    /**
     * Register a one-time event listener
     *
     * @param string $event The name of the event to listen for
     * @param callable $listener The callback function to execute when the event is emitted
     * @param int $priority Priority to sort the listeners
     * @return void
     */
    public function once(string $event, callable $listener, int $priority = 0): void
    {
        $this->addListener($event, $listener, $priority, true);
    }

    /**
     * Get all the listeners for a given event or all of listeners
     *
     * @param string|null $event (optional) The name of the event
     * @return mixed
     */
    public function getListeners(string $event = null): mixed
    {
        if ($event) {

            $this->eventCannotBeEmpty($event);

            return self::$listeners[$event] ?? [];
        }

        return self::$listeners;
    }

    /**
     * Remove all listeners for a given event
     *
     * @param string|null $event (optional) The name of the event
     * @return void
     */
    public function removeListeners(?string $event = null): void
    {
        if ($event) {

            $this->eventCannotBeEmpty($event);

            unset(self::$listeners[$event]);
        } else {
            self::$listeners = [];
        }
    }

    /**
     * Remove the given listener from given event
     *
     * @param string $event The name of the event
     * @param callable $listener The listener to remove
     * @return void
     */
    public function removeListener(string $event, callable $listener): void
    {
        $this->eventCannotBeEmpty($event);

        if (isset(self::$listeners[$event])) {
            foreach (self::$listeners[$event] as $priority => $listeners) {
                foreach ($listeners as $key => $data) {
                    if (($key === 'once' && ($index = array_search($listener, $data, true)) !== false) ||
                        ($index = array_search($listener, $listeners, true)) !== false) {

                        if ($key === 'once') {
                            unset(self::$listeners[$event][$priority][$key][$index]);
                        } else {
                            unset(self::$listeners[$event][$priority][$key]);
                        }

                        if (empty(self::$listeners[$event][$priority][$key])) {
                            unset(self::$listeners[$event][$priority][$key]);
                        }
                        if (empty(self::$listeners[$event][$priority])) {
                            unset(self::$listeners[$event][$priority]);
                        }

                        break 2;
                    }
                }
            }
        }
    }

    /**
     * Emit an event
     *
     * @param string $event The name of the event to emit
     * @param mixed ...$args Parameters to pass to the event listeners
     * @return void
     */
    public function emit(string $event, mixed ...$args): void
    {
        $this->eventCannotBeEmpty($event);

        if (isset(self::$listeners[$event])) {

            ksort(self::$listeners[$event]);

            foreach (self::$listeners[$event] as $priority => $listeners) {
                foreach ($listeners as $key => $data) {

                    if ($key === 'once') {
                        unset(self::$listeners[$event][$priority][$key]);

                        foreach ($data as $listener) {
                            $this->resolve($listener, ...$args);
                        }
                    } else {
                        $this->resolve($data, ...$args);
                    }
                }
            }
        }
    }

    /**
     * Resolve the event to emit
     *
     * @param $listener
     * @param mixed ...$args
     * @return void
     */
    private function resolve($listener, ...$args): void
    {
        if (empty($args)) {
            $this->resolver->callback($listener);
        } else {
            call_user_func($listener, ...$args);
        }
    }

    /**
     * Add the listeners
     *
     * @param string $event The name of the event to listen for
     * @param callable $listener The callback function to execute when the event is emitted
     * @param int $priority Priority to sort the listeners
     * @param bool $once Is listener just for once
     * @return void
     */
    private function addListener(string $event, callable $listener, int $priority = 0, bool $once = false): void
    {
        $this->eventCannotBeEmpty($event);

        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }

        if ($once) {
            self::$listeners[$event][$priority]['once'][] = $listener;
        } else {
            self::$listeners[$event][$priority][] = $listener;
        }
    }

    /**
     * Throw `InvalidArgumentException` on empty string of event name
     *
     * @param $event
     * @return void
     */
    private function eventCannotBeEmpty($event): void
    {
        if ($event === '') {
            throw new InvalidArgumentException('Event name cannot be empty.');
        }
    }
}
