<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Event\Event on(string $event, callable $listener, int $priority = 0)
 * @method static \NovaFrame\Event\Event once(string $event, callable $listener, int $priority = 0)
 * @method static \NovaFrame\Event\Event hasListeners(string $event)
 * @method static \NovaFrame\Event\Event addListener(string $event, callable $listener, int $priority = 0, bool $once = false)
 * @method static \NovaFrame\Event\Event getListeners(?string $event = null)
 * @method static \NovaFrame\Event\Event forget(string $event, ?callable $listener = null)
 * @method static \NovaFrame\Event\Event emit(string $event, array $parameters = [])
 * @method static \NovaFrame\Event\Event defer(string $event, array $parameters)
 * @method static \NovaFrame\Event\Event emitDeferred(?string $event = null)
 * @method static \NovaFrame\Event\Event subscribe(\NovaFrame\Event\EventSubscriber $subscriber)
 * @method static \NovaFrame\Event\Event unsubscribe(\NovaFrame\Event\EventSubscriber $subscriber)
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
