<?php

namespace Tienvx\PactProvider\Listeners;

class EventCollector implements EventCollectorInterface
{
    private array $events = [];

    public function collect(string $eventName, array $data): void
    {
        foreach ($data as $event) {
            $this->events[] = $event;
        }
    }

    public function getAll(): array
    {
        return $this->events;
    }

    public function getSingle(string $eventFqcn): ?object
    {
        foreach ($this->events as $event) {
            if ($event instanceof $eventFqcn) {
                return $event;
            }
        }

        return null;
    }
}
