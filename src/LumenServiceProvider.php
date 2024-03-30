<?php

namespace Tienvx\PactProvider;

use Tienvx\PactProvider\Controllers\MessagesController;
use Tienvx\PactProvider\Controllers\StateChangeController;
use Tienvx\PactProvider\Service\MessageDispatcherManagerInterface;
use Tienvx\PactProvider\Service\StateHandlerManagerInterface;

class LumenServiceProvider extends ServiceProvider
{
    protected function getConfigPath(): string
    {
        return base_path('config/pact_provider.php');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            StateHandlerManagerInterface::class,
            MessageDispatcherManagerInterface::class,
            StateChangeController::class,
            MessagesController::class,
        ];
    }
}
