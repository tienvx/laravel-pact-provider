<?php

namespace Tienvx\PactProvider\Listeners;

interface EventCollectorInterface
{
    public function collect(string $eventName, array $data): void;

    public function getAll(): array;

    public function getSingle(string $eventFqcn): ?object;
}
