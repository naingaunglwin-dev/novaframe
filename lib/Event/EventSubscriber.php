<?php

namespace NovaFrame\Event;

interface EventSubscriber
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * The array keys are event names.
     * The values can be:
     * - string: method name to call
     * - array: [methodName, priority = 0, once = false]
     * - array of arrays: multiple listeners for the same event
     *
     * Example:
     * [
     *   'event.name' => 'methodName',
     *   'another.event' => ['methodName', 10, true],
     *   'multi.listener.event' => [
     *       ['methodOne', 0],
     *       ['methodTwo', 5, true],
     *   ],
     * ]
     *
     * @return array<string, string|array<int, mixed>|array<int, array<int, mixed>>>
     */
    public static function getSubscribedEvents(): array;
}
