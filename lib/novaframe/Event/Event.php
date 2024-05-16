<?php

namespace Nova\Event;

class Event implements EventInterface
{
    /**
     * Registered event listeners
     *
     * @var array
     */
    private static array $listeners = [];

    /**
     * @inheritDoc
     */
    public static function on(string $event, callable $callback): void
    {
        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }

        self::$listeners[$event][] = $callback;
    }

    /**
     * @inheritDoc
     */
    public static function trigger(string $event, mixed ...$params): void
    {
        if (isset(self::$listeners[$event])) {
            foreach (self::$listeners[$event] as $callback) {
                call_user_func($callback, ...$params);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getListeners(string $event): mixed
    {
        return self::$listeners[$event] ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function removeListeners(string $event): void
    {
        unset(self::$listeners[$event]);
    }

    /**
     * @inheritDoc
     */
    public static function system(string $state, callable $callback): void
    {
        self::$listeners["NovaFrame.system.$state"][] = $callback;
    }
}
