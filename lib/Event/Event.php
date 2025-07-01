<?php

namespace NovaFrame\Event;

use NovaFrame\Container\Container;

class Event
{
    /**
     * Registered event listeners.
     * Format: [eventName => [priority => [['listener' => callable, 'once' => bool], ...], ...], ...]
     *
     * @var array<string, array<int, array<int, array{listener: callable, once: bool}>>>
     */
    private array $listeners = [];

    /**
     * Deferred events and their parameters.
     *
     * @var array<string, array>
     */
    private array $deferredEvents = [];

    /**
     * Registered subscribers.
     *
     * @var array<string, EventSubscriber>
     */
    private array $subscribers = [];

    /**
     * Events that have already been fired once.
     *
     * @var array<string>
     */
    private array $firedOnceEvents = [];

    /**
     * Dependency injection container to resolve listener parameters.
     */
    private Container $container;

    /**
     * Event Constructor
     */
    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * Register a listener for an event.
     *
     * @param string   $event    Event name.
     * @param callable $listener Listener callable.
     * @param int      $priority Priority for execution order (higher first).
     * @return $this
     */
    public function on(string $event, callable $listener, int $priority = 0): Event
    {
        return $this->addListener($event, $listener, $priority);
    }

    /**
     * Register a one-time listener that runs only if event has not fired before.
     *
     * @param string   $event    Event name.
     * @param callable $listener Listener callable.
     * @param int      $priority Priority for execution order.
     * @return $this
     */
    public function once(string $event, callable $listener, int $priority = 0): Event
    {
        if (in_array($event, $this->firedOnceEvents)) {
            return $this; // doesn't register if this event has already been fired
        }

        return $this->addListener($event, $listener, $priority, true);
    }

    /**
     * Check if an event has any listeners registered.
     *
     * @param string $event Event name.
     * @return bool
     */
    public function hasListeners(string $event): bool
    {
        return !empty($this->listeners[$event]);
    }

    /**
     * Add a listener to an event, optionally as one-time listener.
     *
     * @param string   $event    Event name.
     * @param callable $listener Listener callable.
     * @param int      $priority Execution priority.
     * @param bool     $once     Whether listener should be called once only.
     * @return $this
     */
    public function addListener(string $event, callable $listener, int $priority = 0, bool $once = false): Event
    {
        $this->listeners[$event][$priority][] = [
            'listener' => $listener,
            'once' => $once
        ];

        return $this;
    }

    /**
     * Get all listeners for an event or all events if no event specified.
     *
     * @param string|null $event Event name or null to get all.
     * @return array<string, mixed>|array<int, array<int, array{listener: callable, once: bool}>>
     */
    public function getListeners(?string $event = null): array
    {
        if (empty($event)) {
            return $this->listeners;
        }

        return $this->listeners[$event] ?? [];
    }

    /**
     * Remove listeners for an event.
     * If no listener specified, remove all listeners for the event.
     * Otherwise, remove only the matching listener.
     *
     * @param string        $event    Event name.
     * @param callable|null $listener Specific listener callable to remove (optional).
     * @return $this
     */
    public function forget(string $event, ?callable $listener = null): Event
    {
        if (empty($event) || !isset($this->listeners[$event])) {
            return $this;
        }

        if (empty($listener)) {
            unset($this->listeners[$event]);

            return $this;
        }

        foreach ($this->listeners[$event] as $priority => $listeners) {
            foreach ($listeners as $index => $value) {
                if (!array_search($listener, $value, true)) {
                    return $this;
                }

                unset($this->listeners[$event][$priority][$index]);

                if (empty($this->listeners[$event][$priority])) {
                    unset($this->listeners[$event][$priority]); // clean up empty listener
                }
            }
        }

        return $this;
    }

    /**
     * Emit/fire an event, invoking all listeners in priority order.
     *
     * @param string $event      Event name.
     * @param array  $parameters Parameters to pass to listeners.
     * @return array<string, array<int, mixed>> Responses from listeners grouped by event and priority.
     */
    public function emit(string $event, array $parameters = []): array
    {
        if (empty($event) || !isset($this->listeners[$event])) {
            return [];
        }

        ksort($this->listeners[$event]);

        $response = [];

        foreach ($this->listeners[$event] as $priority => $listeners) {
            foreach ($listeners as $index => $listener) {
                try {
                    $response[$event][$priority][] = $this->container->get($listener['listener'], $parameters);

                    if ($listener['once']) {
                        unset($this->listeners[$event][$priority][$index]);
                        $this->firedOnceEvents[] = $event;
                    }
                } catch (\Throwable $e) {
                    error_log("Fail to fire event, $event: " . $e->getMessage());
                }
            }
        }

        return $response;
    }

    /**
     * Defer an event emission, storing parameters for later firing.
     *
     * @param string $event      Event name.
     * @param array  $parameters Parameters to defer.
     */
    public function defer(string $event, array $parameters): void
    {
        $this->deferredEvents[$event] = $parameters;
    }

    /**
     * Emit all deferred events or specific deferred event if provided.
     *
     * @param string|null $event Event name or null for all deferred events.
     * @return array Responses from emitted deferred events.
     */
    public function emitDeferred(?string $event = null): array
    {
        $response = [];

        if (!empty($event)) {
            if (!isset($this->deferredEvents[$event])) {
                return $response;
            }

            $events = [$event => $this->deferredEvents[$event]];
        } else {
            $events = $this->deferredEvents;
        }

        foreach ($events as $parameters) {
            $result = $this->emit($event, $parameters);
            $response[$event] = $result[$event];
        }

        $this->deferredEvents = [];

        return $response;
    }

    /**
     * Subscribe an EventSubscriber, registering its events and listeners.
     *
     * @param EventSubscriber $subscriber Subscriber object implementing getSubscribedEvents()
     */
    public function subscribe(EventSubscriber $subscriber): void
    {
        $this->subscribers[spl_object_hash($subscriber)] = $subscriber;

        foreach ($subscriber->getSubscribedEvents() as $event => $params) {
            if (is_string($params)) {
                $this->on($event, [$subscriber, $params]);
            } elseif (is_array($params) || isset($params[0])) {
                if (is_array($params[0])) {
                    // Array of arrays - multiple listeners for this event
                    foreach ($params as $listener) {
                        $method = $listener[0];
                        $priority = $listener[1] ?? 0;
                        $once = $listener[2] ?? false;

                        if ($once) {
                            $this->once($event, [$subscriber, $method], $priority);
                        } else {
                            $this->on($event, [$subscriber, $method], $priority);
                        }
                    }
                } else {
                    // Single array - one listener with potential priority and once flag
                    $method = $params[0];
                    $priority = $params[1] ?? 0;
                    $once = $params[2] ?? false;

                    if ($once) {
                        $this->once($event, [$subscriber, $method], $priority);
                    } else {
                        $this->on($event, [$subscriber, $method], $priority);
                    }
                }
            }
        }
    }

    /**
     * Unsubscribe an EventSubscriber and remove its registered listeners.
     *
     * @param EventSubscriber $subscriber
     */
    public function unsubscribe(EventSubscriber $subscriber): void
    {
        if (!in_array($subscriber, $this->subscribers, true)) {
            return;
        }

        foreach ($subscriber->getSubscribedEvents() as $event => $params) {
            if (!isset($this->listeners[$event])) {
                continue;
            }

            foreach ($this->listeners[$event] as $priority => $listeners) {
                foreach ($listeners as $index => $listener) {
                    if (is_array($listener['listener']) && $listener['listener'][0] === $subscriber) {
                        unset($this->listeners[$event][$priority][$index]);
                    }

                    if (empty($this->listeners[$event][$priority])) {
                        unset($this->listeners[$event][$priority]);
                    }

                    if (empty($this->listeners[$event])) {
                        unset($this->listeners[$event]);
                    }
                }
            }
        }

        foreach ($this->subscribers as $key => $sub) {
            if ($sub === $subscriber) {
                unset($this->subscribers[$key]);
                break;
            }
        }
    }
}
