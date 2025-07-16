<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Event\Event on(string $event, callable $listener, int $priority = 0)
 * @method static \NovaFrame\Event\Event once(string $event, callable $listener, int $priority = 0)
 * @method static bool hasListeners(string $event)
 * @method static \NovaFrame\Event\Event addListener(string $event, callable $listener, int $priority = 0, bool $once = false)
 * @method static array getListeners(?string $event = null)
 * @method static \NovaFrame\Event\Event forget(string $event, ?callable $listener = null)
 * @method static array emit(string $event, array $parameters = [])
 * @method static void defer(string $event, array $parameters)
 * @method static array emitDeferred(?string $event = null)
 * @method static void subscribe(\NovaFrame\Event\EventSubscriber $subscriber)
 * @method static void unsubscribe(\NovaFrame\Event\EventSubscriber $subscriber)
 */
class Event extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Event\Event::class;
    }

    protected static function singleton(): bool
    {
        return true;
    }
}
