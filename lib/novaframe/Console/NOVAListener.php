<?php

namespace Nova\Console;

use Nova\Event\Event;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NOVAListener implements EventSubscriberInterface
{
    private $start;

    public function __construct($start)
    {
        $this->start = $start;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::TERMINATE => 'onTerminate',
        ];
    }

    /**
     * Console Terminate Event
     *
     * @param ConsoleTerminateEvent $event
     * @return void
     */
    public function onTerminate(ConsoleTerminateEvent $event): void
    {
        if (config('console.display_execution_time')) {
            $end = microtime(true);

            $runtime = round($end - $this->start, 2);

            Event::trigger('console_terminate', $event->getOutput(), $runtime);
        } else {
            $event->getOutput()->writeln("\n");
        }
    }
}
